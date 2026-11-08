<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'Sesión no válida.'));
    exit;
}

include_once(__DIR__ . '/../conexion.php');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$q = mysqli_real_escape_string($conexion, $q);

// Busca por cédula, nombre, apellido o "nombre apellido"; máximo 20 resultados
$query = "SELECT id, nombre, apellido, cedula
          FROM pacientes
          WHERE cedula LIKE '%$q%'
             OR nombre LIKE '%$q%'
             OR apellido LIKE '%$q%'
             OR CONCAT(nombre, ' ', apellido) LIKE '%$q%'
          ORDER BY apellido, nombre
          LIMIT 20";
$result = mysqli_query($conexion, $query);

$json = array();
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $json[] = $row;
    }
}

echo json_encode($json);