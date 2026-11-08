<?php
include_once('includes/sesion.php');
$titulo = 'Historia clínica';
$js = 'historial.js';
include_once('includes/header.php');
include_once('includes/menu.php');
?>

<div class="container-fluid mt-4">
    <div id="result"></div>
    <!-- Selector de paciente -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label for="buscar" class="font-weight-bold"><i class="fa fa-search"></i> Buscar paciente</label>
                    <input type="text" id="buscar" class="form-control" placeholder="Cédula, nombre o apellido..." autocomplete="off">
                </div>
                <div class="form-group col-md-7">
                    <label for="paciente_id" class="font-weight-bold"><i class="fa fa-user"></i> Paciente</label>
                    <select id="paciente_id" class="form-control">
                        <option value="">-- Selecciona un paciente --</option>
                    </select>
                    <small class="form-text text-muted">Se muestran máximo 20 resultados. Escribe para afinar la búsqueda.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Tabla de la historia (izquierda) -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa fa-file-text-o"></i> Historia clínica</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Procedimiento</th>
                                    <th>Pieza</th>
                                    <th>Observaciones</th>
                                    <th>Odontólogo</th>
                                </tr>
                            </thead>
                            <tbody id="listado">
                                <tr><td colspan="5" class="text-center text-muted">Selecciona un paciente para ver su historia.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario (derecha) -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fa fa-plus-circle"></i> Nuevo registro</h5>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="form-group">
                            <label for="procedimiento_id">Procedimiento</label>
                            <select id="procedimiento_id" class="form-control" required>
                                <option value="">-- Selecciona --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="fecha">Fecha</label>
                            <input type="date" id="fecha" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="pieza_dental">Pieza dental (opcional)</label>
                            <input type="text" id="pieza_dental" class="form-control" placeholder="Ej: 36">
                        </div>
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea id="observaciones" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-block"><i class="fa fa-floppy-o"></i> Guardar registro</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>