$(document).ready(function() {

    buscarPacientes('');
    cargarProcedimientos();

    // La fecha del formulario empieza con el día de hoy
    $('#fecha').val(new Date().toISOString().slice(0, 10));

    function esc(texto) {
        return $('<div>').text(texto ?? '').html();
    }

    function mostrarAlerta(tipo, mensaje) {
        let template = `<div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            ${esc(mensaje)}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>`;
        $('#result').html(template);
    }

    // ---------- BUSCAR PACIENTES (llena la lista desplegable) ----------
    function buscarPacientes(q) {
        $.get('php/pacientes/buscar.php', {q}, function(pacientes) {
            if (!Array.isArray(pacientes)) {
                window.location.href = 'index.php';
                return;
            }

            let opciones = '<option value="">-- Selecciona un paciente --</option>';
            if (pacientes.length === 0) {
                opciones = '<option value="">Sin resultados</option>';
            }
            pacientes.forEach(p => {
                opciones += `<option value="${p.id}">${esc(p.apellido)} ${esc(p.nombre)} (${esc(p.cedula)})</option>`;
            });
            $('#paciente_id').html(opciones);

            // Si la búsqueda dejó un solo paciente, lo seleccionamos solos
            if (pacientes.length === 1) {
                $('#paciente_id').val(pacientes[0].id);
            }
            listar();
        }, 'json').fail(function() {
            mostrarAlerta('danger', 'Error al buscar pacientes.');
        });
    }

    // Espera 300 ms después de la última tecla para no consultar en cada letra
    let temporizador;
    $('#buscar').on('input', function() {
        clearTimeout(temporizador);
        let q = $(this).val();
        temporizador = setTimeout(function() {
            buscarPacientes(q);
        }, 300);
    });

    // ---------- PROCEDIMIENTOS (lista desplegable del formulario) ----------
    function cargarProcedimientos() {
        $.get('php/procedimientos/listar.php', function(procs) {
            if (!Array.isArray(procs)) return;
            let opciones = '<option value="">-- Selecciona --</option>';
            procs.forEach(p => {
                opciones += `<option value="${p.id}">${esc(p.nombre)}</option>`;
            });
            $('#procedimiento_id').html(opciones);
        }, 'json');
    }

        // Activa el botón de PDF solo cuando hay un paciente elegido
    function actualizarBotonPdf() {
        let paciente_id = $('#paciente_id').val();
        if (paciente_id === '') {
            $('#btn-pdf').addClass('disabled').attr({'href': '#', 'aria-disabled': 'true'});
        } else {
            $('#btn-pdf').removeClass('disabled').attr({'href': 'pdf/generar_pdf.php?paciente_id=' + paciente_id, 'aria-disabled': 'false'});
        }
    }

    // ---------- LISTAR la historia del paciente elegido ----------
    function listar() {
        actualizarBotonPdf();
        let paciente_id = $('#paciente_id').val();

        if (paciente_id === '') {
            $('#listado').html('<tr><td colspan="5" class="text-center text-muted">Selecciona un paciente para ver su historia.</td></tr>');
            return;
        }

        $.get('php/historial/listar.php', {paciente_id}, function(registros) {
            if (!Array.isArray(registros)) {
                window.location.href = 'index.php';
                return;
            }

            if (registros.length === 0) {
                $('#listado').html('<tr><td colspan="5" class="text-center text-muted">Este paciente aún no tiene registros.</td></tr>');
                return;
            }

            let template = '';
            registros.forEach(r => {
                template += `
                    <tr>
                        <td>${esc(r.fecha)}</td>
                        <td>${esc(r.procedimiento)}</td>
                        <td>${esc(r.pieza_dental)}</td>
                        <td>${esc(r.observaciones)}</td>
                        <td>${esc(r.odontologo)}</td>
                    </tr>`;
            });
            $('#listado').html(template);
        }, 'json').fail(function() {
            mostrarAlerta('danger', 'Error al cargar la historia clínica.');
        });
    }

    // Cada vez que cambia el paciente elegido, recargamos su historia
    $('#paciente_id').change(listar);

    // ---------- GUARDAR un registro nuevo ----------
    $('#form').submit(function(e) {
        e.preventDefault();

        // El paciente está fuera del formulario (arriba), así que lo leemos aparte
        let paciente_id = $('#paciente_id').val();
        if (paciente_id === '') {
            mostrarAlerta('warning', 'Primero selecciona un paciente.');
            return;
        }

        const postData = {
            paciente_id: paciente_id,
            procedimiento_id: $('#procedimiento_id').val(),
            fecha: $('#fecha').val(),
            pieza_dental: $('#pieza_dental').val(),
            observaciones: $('#observaciones').val()
        };

        $.post('php/historial/agregar.php', postData, function(response) {
            if (response.estado === 'exito') {
                $('#form').trigger('reset');
                $('#fecha').val(new Date().toISOString().slice(0, 10)); // reset borra la fecha: la reponemos
                listar(); // Recarga la historia del paciente
                mostrarAlerta('success', response.mensaje);
            } else {
                mostrarAlerta('danger', response.mensaje);
            }
        }, 'json').fail(function() {
            mostrarAlerta('danger', 'Error de conexión con el servidor.');
        });
    });

});