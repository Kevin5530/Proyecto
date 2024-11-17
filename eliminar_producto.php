<?php
include 'conexion.php';
include_once 'CRUD.php';
include('encabezado.php'); 

$crud = new CRUD($conexion);

// Eliminar producto
if (isset($_GET['id'])) {
    $producto_id = $_GET['id'];
    $crud->eliminarProducto($conexion, $producto_id);
    header('Location: productos.php'); // Redirige a la lista de productos
}
?>
