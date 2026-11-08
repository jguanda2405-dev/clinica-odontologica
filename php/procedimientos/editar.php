<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'Sesión no válida.'));
    exit;
}

include_once(__DIR__ . '/../conexion.php');

if (!isset($_POST['id'], $_POST['nombre'], $_POST['precio']) || trim($_POST['nombre']) === '' || trim($_POST['precio']) === '') {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'El nombre y el precio son obligatorios.'));
    exit;
}

$id = (int)$_POST['id'];
if ($id <= 0) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'El ID no es válido.'));
    exit;
}

if (!is_numeric($_POST['precio']) || (float)$_POST['precio'] < 0) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'El precio debe ser un número válido, mayor o igual a 0.'));
    exit;
}

$nombre      = mysqli_real_escape_string($conexion, trim($_POST['nombre']));
$descripcion = mysqli_real_escape_string($conexion, trim($_POST['descripcion'] ?? ''));
$precio      = (float)$_POST['precio'];

$query = "UPDATE procedimientos SET nombre = '$nombre', descripcion = '$descripcion', precio = $precio WHERE id = $id";
$result = mysqli_query($conexion, $query);

if (!$result) {
    if (mysqli_errno($conexion) == 1062) {
        $mensaje = 'Ya existe otro procedimiento con ese nombre.';
    } else {
        $mensaje = 'Error al actualizar el procedimiento.';
    }
    echo json_encode(array('estado' => 'error', 'mensaje' => $mensaje));
    exit;
}

echo json_encode(array('estado' => 'exito', 'mensaje' => 'Procedimiento actualizado correctamente.'));