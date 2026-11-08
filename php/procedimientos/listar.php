<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'Sesión no válida.'));
    exit;
}

include_once(__DIR__ . '/../conexion.php');

$json = array();
$query = "SELECT id, nombre, descripcion, precio FROM procedimientos ORDER BY nombre";
$result = mysqli_query($conexion, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $json[] = $row;
    }
}

echo json_encode($json);