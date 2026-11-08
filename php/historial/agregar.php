<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'Sesión no válida.'));
    exit;
}

include_once(__DIR__ . '/../conexion.php');

// 1. Obligatorios: paciente, procedimiento y fecha
if (!isset($_POST['paciente_id'], $_POST['procedimiento_id'], $_POST['fecha'])
    || trim($_POST['paciente_id']) === ''
    || trim($_POST['procedimiento_id']) === ''
    || trim($_POST['fecha']) === '') {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'Selecciona un paciente, un procedimiento y una fecha.'));
    exit;
}

$paciente_id      = (int)$_POST['paciente_id'];
$procedimiento_id = (int)$_POST['procedimiento_id'];
$usuario_id       = (int)$_SESSION['usuario_id']; // El odontólogo sale de la sesión

if ($paciente_id <= 0 || $procedimiento_id <= 0) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'Paciente o procedimiento no válido.'));
    exit;
}

// 2. Validar que la fecha tenga formato real (AAAA-MM-DD)
$fecha = DateTime::createFromFormat('Y-m-d', $_POST['fecha']);
if (!$fecha || $fecha->format('Y-m-d') !== $_POST['fecha']) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'La fecha no es válida.'));
    exit;
}

// 3. Opcionales: pieza dental y observaciones
$fecha_sql     = mysqli_real_escape_string($conexion, $_POST['fecha']);
$pieza_dental  = mysqli_real_escape_string($conexion, trim($_POST['pieza_dental'] ?? ''));
$observaciones = mysqli_real_escape_string($conexion, trim($_POST['observaciones'] ?? ''));

// 4. Insertar
$query = "INSERT INTO historial (paciente_id, procedimiento_id, usuario_id, fecha, pieza_dental, observaciones)
          VALUES ($paciente_id, $procedimiento_id, $usuario_id, '$fecha_sql', '$pieza_dental', '$observaciones')";
$result = mysqli_query($conexion, $query);

if (!$result) {
    // 1452 = la llave foránea no existe (paciente o procedimiento inexistente)
    if (mysqli_errno($conexion) == 1452) {
        $mensaje = 'El paciente o el procedimiento seleccionado no existe.';
    } else {
        $mensaje = 'Error al guardar el registro.';
    }
    echo json_encode(array('estado' => 'error', 'mensaje' => $mensaje));
    exit;
}

echo json_encode(array('estado' => 'exito', 'mensaje' => 'Registro guardado en la historia clínica.'));