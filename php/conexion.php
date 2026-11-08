<?php

mysqli_report(MYSQLI_REPORT_OFF); 

$servidor = "localhost";
$usuario  = "root";
$password = "";
$database = "clinica_db";


$conexion = mysqli_connect($servidor, $usuario, $password, $database); 

if(!$conexion){
    die("Fallo de conexion: " . mysqli_connect_error()); 
}

mysqli_set_charset($conexion, "utf8mb4");