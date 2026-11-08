<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'Sesión no válida.'));
    exit;
}

include_once(__DIR__ . '/../conexion.php');

$paciente_id = isset($_GET['paciente_id']) ? (int)$_GET['paciente_id'] : 0;

$json = array();

if ($paciente_id > 0) {
    $query = "SELECT h.id, h.fecha, h.pieza_dental, h.observaciones,
                p.nombre AS procedimiento,
                CONCAT(u.nombre, ' ', u.apellido) AS odontologo
            FROM historial h
            INNER JOIN procedimientos p ON h.procedimiento_id = p.id
            INNER JOIN usuarios u ON h.usuario_id = u.id
            WHERE h.paciente_id = $paciente_id
            ORDER BY h.fecha DESC, h.id DESC";
    $result = mysqli_query($conexion, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $json[] = $row;
        }
    }
}

echo json_encode($json);