$(document).ready(function() {

    let editar = false;
    listar();

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

    // ---------- LISTAR ----------
    function listar() {
        $.get('php/procedimientos/listar.php', function(procs) {
            if (!Array.isArray(procs)) {
                window.location.href = 'index.php';
                return;
            }

            let template = '';
            procs.forEach(p => {
                template += `
                    <tr id="${p.id}">
                        <td>${p.id}</td>
                        <td>${esc(p.nombre)}</td>
                        <td>${esc(p.descripcion)}</td>
                        <td>$${parseFloat(p.precio).toFixed(2)}</td>
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
            mostrarAlerta('danger', 'Error al cargar los procedimientos.');
        });
    }

    // ---------- SELECCIONAR (cargar datos en el formulario) ----------
    $(document).on('click', '.edit', function() {
        let id = $(this).closest('tr').attr('id');

        $.get('php/procedimientos/seleccionar.php', {id}, function(p) {
            if (p.estado === 'exito') {
                $('#id').val(p.id);
                $('#nombre').val(p.nombre);
                $('#descripcion').val(p.descripcion);
                $('#precio').val(p.precio);
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
            descripcion: $('#descripcion').val(),
            precio: $('#precio').val()
        };

        let url = editar ? 'php/procedimientos/editar.php' : 'php/procedimientos/agregar.php';

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
        if (confirm('¿Estás seguro de eliminar este procedimiento?')) {
            let id = $(this).closest('tr').attr('id');

            $.post('php/procedimientos/eliminar.php', {id}, function(response) {
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