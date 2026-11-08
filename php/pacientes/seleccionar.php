<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'Sesión no válida.'));
    exit;
}

include_once(__DIR__ . '/../conexion.php');

if (!isset($_GET['id'])) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'Falta el parámetro ID.'));
    exit;
}

$id = (int)$_GET['id'];

$query = "SELECT id, nombre, apellido, cedula, fecha_nacimiento, telefono, direccion
          FROM pacientes WHERE id = $id LIMIT 1";
$result = mysqli_query($conexion, $query);

if ($result && $row = mysqli_fetch_assoc($result)) {
    $row['estado'] = 'exito';
    echo json_encode($row);
} else {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'No se encontró el paciente.'));
}