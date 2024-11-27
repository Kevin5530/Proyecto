<?php
include("conexion.php");
include_once 'CRUD.php';


$crud = new CRUD($conexion);

// Obtener todos los pedidos
$pedidos = $crud->obtenerPedidos($conexion);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Pedidos</title>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="css/detalle_pedido.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php include_once 'encabezado.php'; ?>

    <h2>Pedidos</h2>
    <a href="crear_pedido.php" class="btn">Crear Nuevo Pedido</a>

    <h3>Lista de Pedidos</h3>
    <table id="tabla-productos">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($pedido = $pedidos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $pedido['id_pedido']; ?></td>
                    <td><?php echo $pedido['nombre_cliente']; ?></td>
                    <td><?php echo $pedido['fecha_pedido']; ?></td>
                    <td>$<?php echo number_format($pedido['total'], 2); ?></td>
                    <td>
                        <a href="ver_detalle_pedido.php?id_pedido=<?php echo $pedido['id_pedido']; ?>" class="btn-edit">Ver detalles</a>
                        <a href="eliminar_pedido.php?id_pedido=<?php echo $pedido['id_pedido']; ?>" class="btn-delete">Eliminar Pedido</a>
                        <a href="factura.php?id_pedido=<?php echo $pedido['id_pedido']; ?>" class="btn-pagar">Pagar Pedido</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>