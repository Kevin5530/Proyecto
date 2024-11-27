<?php
include 'conexion.php';
include_once 'CRUD.php';

$crud = new CRUD($conexion);

// Agregar producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_producto'])) {
    $nombre_producto = $_POST['nombre_producto'];
    $precio = $_POST['precio'];
    $crud->agregarProducto($conexion, $nombre_producto, $precio);

    // Redirige a la misma página para evitar reenvíos del formulario
    header("Location: productos.php");
    exit();
}

// Obtener productos
$productos = $crud->obtenerProductos($conexion);

if (!$productos) {
    // Si no hay productos o la consulta falló, muestra un mensaje de error
    echo "<p style='color: red; text-align: center;'>Error al obtener los productos. Verifica la conexión o la consulta.</p>";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="css/estilo.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/productos.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php include_once 'encabezado.php'; ?>

    <h2>Agregar Producto</h2>
    <form action="productos.php" method="POST" class="form-producto">
        <label for="nombre_producto">Nombre del Producto:</label>
        <input type="text" name="nombre_producto" placeholder="Ej. Arroz" required>

        <label for="precio">Precio:</label>
        <input type="number" name="precio" placeholder="Ej. 50.00" step="0.01" required>

        <button type="submit" name="agregar_producto">Agregar Producto</button>
    </form>

    <h2>Lista de Productos</h2>
    <table id="tabla-productos">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($productos): ?>
                <?php while ($producto = $productos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $producto['id_producto']; ?></td>
                        <td><?php echo $producto['nombre_producto']; ?></td>
                        <td><?php echo $producto['precio']; ?></td>
                        <td>
                            <a href="editar_producto.php?id=<?php echo $producto['id_producto']; ?>">Editar</a>
                            <a href="eliminar_producto.php?id=<?php echo $producto['id_producto']; ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center;">No hay productos disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>