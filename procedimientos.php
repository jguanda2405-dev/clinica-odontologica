<?php
include_once('includes/sesion.php');
$titulo = 'Procedimientos';
$js = 'procedimientos.js';
include_once('includes/header.php');
include_once('includes/menu.php');
?>

<div class="container-fluid mt-4">
    <div id="result"></div>

    <div class="row">
        <!-- Tabla (izquierda) -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa fa-list"></i> Procedimientos ofrecidos</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Precio</th>
                                    <th class="text-center" colspan="2">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="listado"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario (derecha) -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fa fa-medkit"></i> Formulario de procedimiento</h5>
                </div>
                <div class="card-body">
                    <form id="form">
                        <input type="hidden" id="id">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción (opcional)</label>
                            <textarea id="descripcion" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio</label>
                            <input type="number" id="precio" class="form-control" step="0.01" min="0" required>
                        </div>
                        <button type="submit" class="btn btn-success btn-block"><i class="fa fa-floppy-o"></i> Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>