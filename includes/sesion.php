<?php
session_start();

// Si no hay marca de sesión, mandamos al login y detenemos todo
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}