<?php
session_start();

// Protección: sin sesión, volvemos al login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once(__DIR__ . '/fpdf/fpdf.php');
include_once(__DIR__ . '/../php/conexion.php');

// FPDF trabaja en ISO-8859-1, no en UTF-8. Esta función convierte el texto
// para que las tildes y la ñ salgan bien en el PDF.
function t($texto) {
    return mb_convert_encoding((string)$texto, 'ISO-8859-1', 'UTF-8');
}

// 1. Validar el paciente recibido por GET
$paciente_id = isset($_GET['paciente_id']) ? (int)$_GET['paciente_id'] : 0;
if ($paciente_id <= 0) {
    die('Paciente no válido.');
}

// 2. Datos del paciente
$result = mysqli_query($conexion, "SELECT * FROM pacientes WHERE id = $paciente_id LIMIT 1");
$paciente = $result ? mysqli_fetch_assoc($result) : null;
if (!$paciente) {
    die('No se encontró el paciente.');
}

// 3. Historia clínica del paciente (mismo JOIN que usamos en listar.php)
$query = "SELECT h.fecha, h.pieza_dental, h.observaciones,
                 p.nombre AS procedimiento,
                 CONCAT(u.nombre, ' ', u.apellido) AS odontologo
          FROM historial h
          INNER JOIN procedimientos p ON h.procedimiento_id = p.id
          INNER JOIN usuarios u ON h.usuario_id = u.id
          WHERE h.paciente_id = $paciente_id
          ORDER BY h.fecha DESC, h.id DESC";
$registros = mysqli_query($conexion, $query);

// 4. Clase propia: encabezado y pie de página que se repiten en cada hoja
class HistoriaPDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, t('Clínica Odontológica'), 0, 1, 'C');
        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 7, t('Historia clínica del paciente'), 0, 1, 'C');
        $this->Ln(4);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, t('Página ') . $this->PageNo() . ' de {nb}', 0, 0, 'C');
    }
}

$pdf = new HistoriaPDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// 5. Bloque de datos del paciente
$edad = (new DateTime($paciente['fecha_nacimiento']))->diff(new DateTime())->y;

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(0, 8, t('Datos del paciente'), 0, 1, 'L', true);
$pdf->Ln(2);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 7, t('Nombre: ' . $paciente['nombre'] . ' ' . $paciente['apellido']), 0, 1);
$pdf->Cell(0, 7, t('Cédula: ' . $paciente['cedula']), 0, 1);
$pdf->Cell(0, 7, t('Fecha de nacimiento: ' . date('d/m/Y', strtotime($paciente['fecha_nacimiento'])) . ' (' . $edad . ' años)'), 0, 1);
$pdf->Cell(0, 7, t('Teléfono: ' . $paciente['telefono']), 0, 1);
$pdf->MultiCell(0, 7, t('Dirección: ' . $paciente['direccion']));
$pdf->Ln(4);

// 6. Registros de la historia clínica
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, t('Procedimientos realizados'), 0, 1, 'L', true);
$pdf->Ln(2);

if ($registros && mysqli_num_rows($registros) > 0) {
    while ($r = mysqli_fetch_assoc($registros)) {
        // Línea principal: fecha y procedimiento
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 7, t(date('d/m/Y', strtotime($r['fecha'])) . ' - ' . $r['procedimiento']), 0, 1);

        $pdf->SetFont('Arial', '', 10);
        if (trim($r['pieza_dental']) !== '') {
            $pdf->Cell(0, 6, t('Pieza dental: ' . $r['pieza_dental']), 0, 1);
        }
        if (trim($r['observaciones']) !== '') {
            $pdf->MultiCell(0, 6, t('Observaciones: ' . $r['observaciones']));
        }
        $pdf->Cell(0, 6, t('Odontólogo: ' . $r['odontologo']), 0, 1);

        // Línea separadora entre registros
        $pdf->Ln(1);
        $pdf->Line($pdf->GetX(), $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(3);
    }
} else {
    $pdf->SetFont('Arial', 'I', 11);
    $pdf->Cell(0, 8, t('Este paciente aún no tiene registros.'), 0, 1);
}

// 7. Mostrar el PDF en el navegador. El nombre del archivo se limpia por seguridad.
$nombre_archivo = 'historia_clinica_' . preg_replace('/[^A-Za-z0-9_-]/', '', $paciente['cedula']) . '.pdf';
$pdf->Output('I', $nombre_archivo);