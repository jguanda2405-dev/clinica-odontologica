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

$query = "DELETE FROM pacientes WHERE id = $id";
$result = mysqli_query($conexion, $query);

if (!$result) {
    // 1451 = no se puede borrar porque otra tabla (historial) lo referencia
    if (mysqli_errno($conexion) == 1451) {
        $mensaje = 'No se puede eliminar: el paciente tiene historia clínica registrada.';
    } else {
        $mensaje = 'Error al eliminar el paciente.';
    }
    echo json_encode(array('estado' => 'error', 'mensaje' => $mensaje));
    exit;
}

// Verificar que realmente se borró una fila (que el ID existía)
if (mysqli_affected_rows($conexion) > 0) {
    echo json_encode(array('estado' => 'exito', 'mensaje' => 'Paciente eliminado correctamente.'));
} else {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'No se encontró el paciente.'));
}