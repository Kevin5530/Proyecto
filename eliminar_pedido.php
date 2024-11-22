<?php
include 'conexion.php';
include_once 'CRUD.php';
include('encabezado.php');

$crud = new CRUD($conexion);

// Eliminar pedido
if (isset($_GET['id_pedido'])) {
    $pedido_id = $_GET['id_pedido'];
    $crud->eliminarPedido($conexion, $pedido_id);
    header('Location: pedido.php'); // Redirige a la lista de productos
}
