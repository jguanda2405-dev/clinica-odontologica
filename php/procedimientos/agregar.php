<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'Sesión no válida.'));
    exit;
}

include_once(__DIR__ . '/../conexion.php');

// La descripción es opcional, así que solo validamos nombre y precio
if (!isset($_POST['nombre'], $_POST['precio']) || trim($_POST['nombre']) === '' || trim($_POST['precio']) === '') {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'El nombre y el precio son obligatorios.'));
    exit;
}

// El precio debe ser un número y no puede ser negativo
if (!is_numeric($_POST['precio']) || (float)$_POST['precio'] < 0) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'El precio debe ser un número válido, mayor o igual a 0.'));
    exit;
}

$nombre      = mysqli_real_escape_string($conexion, trim($_POST['nombre']));
$descripcion = mysqli_real_escape_string($conexion, trim($_POST['descripcion'] ?? ''));
$precio      = (float)$_POST['precio'];

// El precio va SIN comillas porque es un número, no un texto
$query = "INSERT INTO procedimientos (nombre, descripcion, precio) VALUES ('$nombre', '$descripcion', $precio)";
$result = mysqli_query($conexion, $query);

if (!$result) {
    // 1062 = valor duplicado en una columna UNIQUE (el nombre)
    if (mysqli_errno($conexion) == 1062) {
        $mensaje = 'Ya existe un procedimiento con ese nombre.';
    } else {
        $mensaje = 'Error al registrar el procedimiento.';
    }
    echo json_encode(array('estado' => 'error', 'mensaje' => $mensaje));
    exit;
}

echo json_encode(array('estado' => 'exito', 'mensaje' => 'Procedimiento registrado correctamente.'));