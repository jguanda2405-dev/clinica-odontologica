<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'Sesión no válida.'));
    exit;
}

include_once(__DIR__ . '/../conexion.php');

// 1. Verificar que lleguen los 6 campos y que ninguno esté vacío
$campos = array('nombre', 'apellido', 'cedula', 'fecha_nacimiento', 'telefono', 'direccion');
foreach ($campos as $campo) {
    if (!isset($_POST[$campo]) || trim($_POST[$campo]) === '') {
        echo json_encode(array('estado' => 'error', 'mensaje' => 'Por favor, completa todos los campos.'));
        exit;
    }
}

// 2. Validar que la fecha tenga formato real (AAAA-MM-DD)
$fecha = DateTime::createFromFormat('Y-m-d', $_POST['fecha_nacimiento']);
if (!$fecha || $fecha->format('Y-m-d') !== $_POST['fecha_nacimiento']) {
    echo json_encode(array('estado' => 'error', 'mensaje' => 'La fecha de nacimiento no es válida.'));
    exit;
}

// 3. Limpiar y escapar los datos
$nombre    = mysqli_real_escape_string($conexion, trim($_POST['nombre']));
$apellido  = mysqli_real_escape_string($conexion, trim($_POST['apellido']));
$cedula    = mysqli_real_escape_string($conexion, trim($_POST['cedula']));
$fecha_nac = mysqli_real_escape_string($conexion, $_POST['fecha_nacimiento']);
$telefono  = mysqli_real_escape_string($conexion, trim($_POST['telefono']));
$direccion = mysqli_real_escape_string($conexion, trim($_POST['direccion']));

// 4. Insertar
$query = "INSERT INTO pacientes (nombre, apellido, cedula, fecha_nacimiento, telefono, direccion)
          VALUES ('$nombre', '$apellido', '$cedula', '$fecha_nac', '$telefono', '$direccion')";
$result = mysqli_query($conexion, $query);

if (!$result) {
    // 1062 = valor duplicado en una columna UNIQUE (la cédula)
    if (mysqli_errno($conexion) == 1062) {
        $mensaje = 'Ya existe un paciente registrado con esa cédula.';
    } else {
        $mensaje = 'Error al registrar el paciente.';
    }
    echo json_encode(array('estado' => 'error', 'mensaje' => $mensaje));
    exit;
}

echo json_encode(array('estado' => 'exito', 'mensaje' => 'Paciente registrado correctamente.'));