<?php
$host = "localhost";
$usuario = "usuario_proyecto";
$password = "";
$dbname = "proyecto";

$conexion = new mysqli($host, $usuario, $password, $dbname);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
