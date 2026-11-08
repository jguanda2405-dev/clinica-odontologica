<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'Sesión no válida.'));
    exit;
}

include_once(__DIR__ . '/../conexion.php');

if (!isset($_POST['id'])) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'Falta el parámetro ID.'));
    exit;
}

$id = (int)$_POST['id'];
if ($id <= 0) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'El ID no es válido.'));
    exit;
}

$query = "DELETE FROM procedimientos WHERE id = $id";
$result = mysqli_query($conexion, $query);

if (!$result) {
    // 1451 = el procedimiento ya se usó en alguna historia clínica
    if (mysqli_errno($conexion) == 1451) {
        $mensaje = 'No se puede eliminar: el procedimiento está usado en historias clínicas.';
    } else {
        $mensaje = 'Error al eliminar el procedimiento.';
    }
    echo json_encode(array('estado' => 'error', 'mensaje' => $mensaje));
    exit;
}

// Verificar que realmente se borró una fila (que el ID existía)
if (mysqli_affected_rows($conexion) > 0) {
    echo json_encode(array('estado' => 'exito', 'mensaje' => 'Procedimiento eliminado correctamente.'));
} else {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'No se encontró el procedimiento.'));
}