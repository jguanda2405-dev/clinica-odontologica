<?php
session_start();

// Si ya inició sesión, no tiene sentido mostrar el login: lo mandamos al panel
if (isset($_SESSION['usuario_id'])) {
    header('Location: panel.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Clínica Odontológica</title>
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/app.css">
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-5 col-lg-4">

            <div class="text-center mb-4">
                <h2 class="font-weight-bold"><i class="fa fa-heartbeat"></i> Clínica Odontológica</h2>
                <p class="text-muted">Inicia sesión para continuar</p>
            </div>

            <!-- Aquí se inyectan los mensajes de error -->
            <div id="result"></div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form id="form-login">
                        <div class="form-group">
                            <label for="correo"><i class="fa fa-envelope"></i> Correo</label>
                            <input type="email" id="correo" class="form-control" placeholder="Ingrese su correo..." required>
                        </div>
                        <div class="form-group">
                            <label for="contrasena"><i class="fa fa-lock"></i> Contraseña</label>
                            <input type="password" id="contrasena" class="form-control" placeholder="Ingrese su contraseña..." required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fa fa-sign-in"></i> Entrar
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/login.js"></script>
</body>
</html>