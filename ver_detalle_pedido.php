<?php
include("conexion.php");
include_once 'CRUD.php';

$crud = new CRUD($conexion);

// Validar si el ID del pedido está definido
if (!isset($_GET['id_pedido']) || empty($_GET['id_pedido'])) {
    die("ID del pedido no proporcionado.");
}

$id_pedido = (int)$_GET['id_pedido'];

// Obtener detalles del pedido
$pedido = $crud->obtenerDetallePedidoId($conexion, $id_pedido);

// Validar si el pedido existe
if (!$pedido) {
    die("Pedido no encontrado.");
}

// Obtener los productos del pedido
$detalle = $crud->obtenerProductosId($conexion, $id_pedido);

// Manejar el envío a la cocina
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_cocina'])) {
    $id_pedido = $_POST['id_pedido'];

    // Usar la función centralizada
    if ($crud->enviarPedidoACocina($conexion, $id_pedido)) {
        echo "<p style='color: green;'>Pedido enviado a cocina con éxito.</p>";
        header("Location: cocina.php?id_pedido=$id_pedido");
        exit();
    } else {
        echo "<p style='color: red;'>Error al enviar el pedido a la cocina.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalle del Pedido</title>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="css/detalle_pedido.css">
</head>

<body>
    <?php include_once 'encabezado.php'; ?>

    <div class="detalle-pedido">
        <h2>Detalle del Pedido #<?php echo $pedido['id_pedido']; ?></h2>
        <p><strong>Cliente:</strong> <?php echo $pedido['nombre_cliente']; ?></p>
        <p><strong>Fecha:</strong> <?php echo $pedido['fecha_pedido']; ?></p>
        <p><strong>Total:</strong> $<?php echo number_format($pedido['total'], 2); ?></p>
    </div>
    <h3>Productos</h3>
    <table id="tabla-detalle">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $detalle->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $fila['nombre_producto']; ?></td>
                    <td><?php echo $fila['cantidad']; ?></td>
                    <td>$<?php echo number_format($fila['subtotal'], 2); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <form method="POST" action="">
        <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
        <button type="submit" name="enviar_cocina">Enviar Pedido a Cocina</button>
    </form>
</body>

</html>