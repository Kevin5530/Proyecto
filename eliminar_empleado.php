<?php
include 'conexion.php';
include_once 'CRUD.php';
include('encabezado.php');

$crud = new CRUD($conexion);

// Eliminar pedido
if (isset($_GET['id_empleado'])) {
    $pedido_id = $_GET['id_empleado'];
    $crud->eliminarEmpleado($conexion, $pedido_id);
    header('Location: empleados.php'); // Redirige a la lista de productos
    echo "Empleado eliminado con éxito.";
}
