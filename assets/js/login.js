$(document).ready(function() {

    $('#form-login').submit(function(e) {
        e.preventDefault(); // Evita que la página se recargue

        const postData = {
            correo: $('#correo').val(),
            contrasena: $('#contrasena').val()
        };

        $.post('php/login.php', postData, function(response) {
            // Revisamos el 'estado' que responde el PHP
            if (response.estado === 'exito') {
                window.location.href = 'panel.php';
            } else {
                mostrarError(response.mensaje);
            }
        }, 'json').fail(function() {
            mostrarError('Error de conexión con el servidor.');
        });
    });

    function mostrarError(mensaje) {
        let template = '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
        template += mensaje;
        template += '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>';
        template += '</div>';
        $('#result').html(template);
    }
});