<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'Sesión no válida.'));
    exit;
}

include_once(__DIR__ . '/../conexion.php');

$campos = array('id', 'nombre', 'apellido', 'cedula', 'fecha_nacimiento', 'telefono', 'direccion');
foreach ($campos as $campo) {
    if (!isset($_POST[$campo]) || trim($_POST[$campo]) === '') {
        echo json_encode(array('estado' => 'error', 'mensaje' => 'Por favor, completa todos los campos.'));
        exit;
    }
}

$id = (int)$_POST['id'];
if ($id <= 0) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'El ID no es válido.'));
    exit;
}

$fecha = DateTime::createFromFormat('Y-m-d', $_POST['fecha_nacimiento']);
if (!$fecha || $fecha->format('Y-m-d') !== $_POST['fecha_nacimiento']) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'La fecha de nacimiento no es válida.'));
    exit;
}

$nombre    = mysqli_real_escape_string($conexion, trim($_POST['nombre']));
$apellido  = mysqli_real_escape_string($conexion, trim($_POST['apellido']));
$cedula    = mysqli_real_escape_string($conexion, trim($_POST['cedula']));
$fecha_nac = mysqli_real_escape_string($conexion, $_POST['fecha_nacimiento']);
$telefono  = mysqli_real_escape_string($conexion, trim($_POST['telefono']));
$direccion = mysqli_real_escape_string($conexion, trim($_POST['direccion']));

$query = "UPDATE pacientes SET nombre = '$nombre', apellido = '$apellido', cedula = '$cedula',
          fecha_nacimiento = '$fecha_nac', telefono = '$telefono', direccion = '$direccion'
          WHERE id = $id";
$result = mysqli_query($conexion, $query);

if (!$result) {
    if (mysqli_errno($conexion) == 1062) {
        $mensaje = 'Ya existe otro paciente con esa cédula.';
    } else {
        $mensaje = 'Error al actualizar el paciente.';
    }
    echo json_encode(array('estado' => 'error', 'mensaje' => $mensaje));
    exit;
}

echo json_encode(array('estado' => 'exito', 'mensaje' => 'Paciente actualizado correctamente.'));