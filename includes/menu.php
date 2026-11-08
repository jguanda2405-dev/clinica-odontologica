<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="panel.php"><i class="fa fa-heartbeat"></i> Clínica</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menuNav">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="menuNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a class="nav-link" href="panel.php"><i class="fa fa-home"></i> Panel</a></li>
            <li class="nav-item"><a class="nav-link" href="pacientes.php"><i class="fa fa-users"></i> Pacientes</a></li>
            <li class="nav-item"><a class="nav-link" href="procedimientos.php"><i class="fa fa-medkit"></i> Procedimientos</a></li>
            <li class="nav-item"><a class="nav-link" href="historial.php"><i class="fa fa-file-text-o"></i> Historia clínica</a></li>
        </ul>
        <span class="navbar-text mr-3">
            <i class="fa fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
        </span>
        <a class="btn btn-outline-light btn-sm" href="php/logout.php"><i class="fa fa-sign-out"></i> Salir</a>
    </div>
</nav>