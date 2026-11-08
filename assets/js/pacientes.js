$(document).ready(function() {

    let editar = false;
    listar();

    // Evita que un dato con código HTML se ejecute en la tabla
    function esc(texto) {
        return $('<div>').text(texto).html();
    }

    // Muestra una alerta Bootstrap. tipo: success, danger, warning
    function mostrarAlerta(tipo, mensaje) {
        let template = `<div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            ${esc(mensaje)}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>`;
        $('#result').html(template);
    }

    // ---------- LISTAR ----------
    function listar() {
        $.get('php/pacientes/listar.php', function(users) {
            if (!Array.isArray(users)) {
                window.location.href = 'index.php';
                return;
            }

            let template = '';
            users.forEach(p => {
                template += `
                    <tr id="${p.id}">
                        <td>${p.id}</td>
                        <td>${esc(p.nombre)}</td>
                        <td>${esc(p.apellido)}</td>
                        <td>${esc(p.cedula)}</td>
                        <td>${esc(p.telefono)}</td>
                        <td class="text-center">
                            <button class="edit btn btn-warning btn-sm"><i class="fa fa-pencil-square-o"></i> Editar</button>
                        </td>
                        <td class="text-center">
                            <button class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i> Eliminar</button>
                        </td>
                    </tr>`;
            });
            $('#listado').html(template);
        }, 'json').fail(function() {
            mostrarAlerta('danger', 'Error al cargar los pacientes.');
        });
    }

    // ---------- SELECCIONAR (cargar datos en el formulario) ----------
    $(document).on('click', '.edit', function() {
        let id = $(this).closest('tr').attr('id');

        $.get('php/pacientes/seleccionar.php', {id}, function(p) {
            if (p.estado === 'exito') {
                $('#id').val(p.id);
                $('#nombre').val(p.nombre);
                $('#apellido').val(p.apellido);
                $('#cedula').val(p.cedula);
                $('#fecha_nacimiento').val(p.fecha_nacimiento);
                $('#telefono').val(p.telefono);
                $('#direccion').val(p.direccion);
                editar = true;
            } else {
                mostrarAlerta('danger', p.mensaje);
            }
        }, 'json').fail(function() {
            mostrarAlerta('danger', 'Error de conexión con el servidor.');
        });
    });

    // ---------- GUARDAR (agregar o editar) ----------
    $('#form').submit(function(e) {
        e.preventDefault();

        const postData = {
            id: $('#id').val(),
            nombre: $('#nombre').val(),
            apellido: $('#apellido').val(),
            cedula: $('#cedula').val(),
            fecha_nacimiento: $('#fecha_nacimiento').val(),
            telefono: $('#telefono').val(),
            direccion: $('#direccion').val()
        };

        let url = editar ? 'php/pacientes/editar.php' : 'php/pacientes/agregar.php';

        $.post(url, postData, function(response) {
            if (response.estado === 'exito') {
                $('#form').trigger('reset');
                $('#id').val('');
                editar = false;
                listar();
                mostrarAlerta('success', response.mensaje);
            } else {
                mostrarAlerta('danger', response.mensaje);
            }
        }, 'json').fail(function() {
            mostrarAlerta('danger', 'Error de conexión con el servidor.');
        });
    });

    // ---------- ELIMINAR ----------
    $(document).on('click', '.delete', function() {
        if (confirm('¿Estás seguro de eliminar este paciente?')) {
            let id = $(this).closest('tr').attr('id');

            $.post('php/pacientes/eliminar.php', {id}, function(response) {
                if (response.estado === 'exito') {
                    listar();
                    mostrarAlerta('success', response.mensaje);
                } else {
                    mostrarAlerta('danger', response.mensaje);
                }
            }, 'json').fail(function() {
                mostrarAlerta('danger', 'Error de conexión con el servidor.');
            });
        }
    });

});