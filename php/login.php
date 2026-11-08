<?php
session_start();
include_once(__DIR__ . '/conexion.php');
header('Content-Type: application/json');

if (isset($_POST['correo'], $_POST['contrasena'])) {

    $correo     = mysqli_real_escape_string($conexion, trim($_POST['correo']));
    $contrasena = $_POST['contrasena'];

    // Buscar al usuario SOLO por correo
    $query  = "SELECT id, nombre, apellido, contrasena FROM usuarios WHERE correo = '$correo' LIMIT 1";
    $result = mysqli_query($conexion, $query);

    if ($result && $row = mysqli_fetch_assoc($result)) {

        // Comparar lo escrito con el hash guardado
        if (password_verify($contrasena, $row['contrasena'])) {
            session_regenerate_id(true);
            $_SESSION['usuario_id']     = $row['id'];
            $_SESSION['usuario_nombre'] = $row['nombre'] . ' ' . $row['apellido'];

            echo json_encode(array('estado' => 'exito', 'mensaje' => 'Bienvenido'));
            exit;
        }
    }

    // Llegamos aquí si el correo no existe O la contraseña no coincide
    echo json_encode(array('estado' => 'error', 'mensaje' => 'Correo o contraseña incorrectos.'));

} else {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'Faltan datos en la solicitud.'));
}