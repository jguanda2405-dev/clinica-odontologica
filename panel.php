<?php
include_once('includes/sesion.php');
include_once('php/conexion.php');

// Contar registros para las tarjetas
$total_pacientes = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM pacientes"))['total'];
$total_procs     = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM procedimientos"))['total'];
$total_historial = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM historial"))['total'];

$titulo = 'Panel principal';
include_once('includes/header.php');
include_once('includes/menu.php');
?>

<div class="container mt-4">
    <h3 class="font-weight-bold">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></h3>
    <hr>

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-info shadow-sm">
                <div class="card-body">
                    <h5><i class="fa fa-users"></i> Pacientes</h5>
                    <h2><?php echo $total_pacientes; ?></h2>
                    <a href="pacientes.php" class="text-white">Ver pacientes &raquo;</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h5><i class="fa fa-medkit"></i> Procedimientos</h5>
                    <h2><?php echo $total_procs; ?></h2>
                    <a href="procedimientos.php" class="text-white">Ver procedimientos &raquo;</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <h5><i class="fa fa-file-text-o"></i> Registros clínicos</h5>
                    <h2><?php echo $total_historial; ?></h2>
                    <a href="historial.php" class="text-white">Ver historias &raquo;</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>