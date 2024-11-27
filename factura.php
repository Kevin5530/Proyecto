<?php
include 'conexion.php';
include_once 'CRUD.php';

$crud = new CRUD();

$pedido = null;
$factura = null;

if (isset($_GET['id_pedido'])) {
    $id_pedido = $_GET['id_pedido'];

    // Obtener el detalle del pedido
    $pedido = $crud->obtenerDetallePedidoId($conexion, $id_pedido);

    // Verificar si el pedido existe
    if (!$pedido) {
        echo "Pedido no encontrado.";
        exit;
    }

    // Obtener la factura correspondiente
    $factura = $crud->obtenerFacturaPorPedido($conexion, $id_pedido);

    // Verificar si la factura existe
    if (!$factura) {
        echo "Factura no encontrada para este pedido.";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_factura'])) {
    // Marcar como pagado cuando se envíe el formulario
    $id_factura = $_POST['id_factura'];

    if ($crud->marcarComoPagado($conexion, $id_factura)) {
        echo "Factura marcada como pagada.";
    } else {
        echo "Error al actualizar el estado de la factura.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Factura del Pedido</title>
</head>

<body>
    <h2>Factura del Pedido</h2>

    <?php if ($pedido && $factura): ?>
        <table>
            <tr>
                <th>ID Pedido:</th>
                <td><?php echo $pedido['id_pedido']; ?></td>
            </tr>
            <tr>
                <th>Cliente:</th>
                <td><?php echo $pedido['nombre_cliente']; ?></td>
            </tr>
            <tr>
                <th>Fecha:</th>
                <td><?php echo $pedido['fecha_pedido']; ?></td>
            </tr>
            <tr>
                <th>Total:</th>
                <td>$<?php echo number_format($pedido['total'], 2); ?></td>
            </tr>
            <tr>
                <th>Estado:</th>
                <td><?php echo $factura['estado']; ?></td>
            </tr>
        </table>

        <?php if ($factura['estado'] === 'pendiente'): ?>
            <form method="POST">
                <input type="hidden" name="id_factura" value="<?php echo $factura['id_factura']; ?>">
                <button type="submit">Marcar como Pagado</button>
            </form>
        <?php else: ?>
            <p>La factura ya está pagada.</p>
        <?php endif; ?>

    <?php else: ?>
        <p>No se encontraron detalles de pedido o factura.</p>
    <?php endif; ?>

    <a href="pedido.php">Volver a Pedidos</a>
</body>

</html>