<?php
session_start();
header('Content-Type: application/json');

// Protección del backend: sin sesión, no hay datos
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'Sesión no válida.'));
    exit;
}

include_once(__DIR__ . '/../conexion.php');

$json = array();
$query = "SELECT id, nombre, apellido, cedula, telefono FROM pacientes ORDER BY apellido, nombre";
$result = mysqli_query($conexion, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $json[] = $row;
    }
}

echo json_encode($json);