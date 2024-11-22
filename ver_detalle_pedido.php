<?php
include("conexion.php");
$id_pedido = $_GET['id_pedido'];

// Obtener detalles del pedido
$pedido = $conexion->query("
    SELECT p.id_pedido, c.nombre_cliente, p.fecha_pedido, p.total
    FROM pedido p
    JOIN clientes c ON p.id_cliente = c.id_cliente
    WHERE p.id_pedido = $id_pedido
")->fetch_assoc();

// Obtener los productos del pedido
$detalle = $conexion->query("
    SELECT pr.nombre_producto, d.cantidad, d.subtotal
    FROM detalle_pedido d
    JOIN productos pr ON d.id_producto = pr.id_producto
    WHERE d.id_pedido = $id_pedido
");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalle del Pedido</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>

<body>
    <?php include_once 'encabezado.php'; ?>

    <h2>Detalle del Pedido #<?php echo $pedido['id_pedido']; ?></h2>
    <p>Cliente: <?php echo $pedido['nombre_cliente']; ?></p>
    <p>Fecha: <?php echo $pedido['fecha_pedido']; ?></p>
    <p>Total: $<?php echo number_format($pedido['total'], 2); ?></p>

    <h3>Productos</h3>
    <table>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
        </tr>
        <?php while ($fila = $detalle->fetch_assoc()): ?>
            <tr>
                <td><?php echo $fila['nombre_producto']; ?></td>
                <td><?php echo $fila['cantidad']; ?></td>
                <td>$<?php echo $fila['subtotal']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>