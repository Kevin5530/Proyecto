<?php
include("conexion.php");

// Obtener lista de clientes
$clientes = $conexion->query("SELECT id_cliente, nombre_cliente FROM clientes");

// Obtener lista de productos
$productos = $conexion->query("SELECT id_producto, nombre_producto, precio FROM productos");

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = $_POST['id_cliente'];
    $productos_seleccionados = $_POST['productos'];
    $cantidades = $_POST['cantidades'];

    // Crear pedido
    $conexion->query("INSERT INTO pedido (id_cliente, total) VALUES ($id_cliente, 0.0)");
    $id_pedido = $conexion->insert_id;

    // Insertar productos al detalle del pedido
    $total = 0;
    foreach ($productos_seleccionados as $index => $id_producto) {
        $cantidad = (float)$cantidades[$index];
        $precio = (float)$conexion->query("SELECT precio FROM productos WHERE id_producto = $id_producto")->fetch_assoc()['precio'];
        $subtotal = $cantidad * $precio;
        $total += $subtotal;

        $conexion->query("INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, subtotal)
                          VALUES ($id_pedido, $id_producto, $cantidad, $subtotal)");
    }

    // Actualizar el total del pedido
    $conexion->query("UPDATE pedido SET total = $total WHERE id_pedido = $id_pedido");

    header("Location: pedido.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Pedido</title>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="css/productos.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php include_once 'encabezado.php'; ?>

    <h2>Crear Pedido</h2>
    <form method="POST" class="form-producto">
        <label for="id_cliente">Cliente:</label>
        <select id="id_cliente" name="id_cliente" required>
            <?php while ($cliente = $clientes->fetch_assoc()): ?>
                <option value="<?php echo $cliente['id_cliente']; ?>"><?php echo $cliente['nombre_cliente']; ?></option>
            <?php endwhile; ?>
        </select>

        <h3>Seleccionar Productos</h3>
        <div id="productos">
            <?php while ($producto = $productos->fetch_assoc()): ?>
                <div>
                    <label>
                        <input type="checkbox" name="productos[<?php echo $producto['id_producto']; ?>]" value="<?php echo $producto['id_producto']; ?>">
                        <?php echo $producto['nombre_producto']; ?> ($<?php echo $producto['precio']; ?>)
                    </label>
                    <input type="number" name="cantidades[<?php echo $producto['id_producto']; ?>]" placeholder="Cantidad" min="1">
                </div>
            <?php endwhile; ?>
        </div>

        <button type="submit">Crear Pedido</button>
    </form>
</body>

</html>