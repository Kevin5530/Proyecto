<?php
include 'conexion.php';
include_once 'CRUD.php';

$crud = new CRUD($conexion);

if (isset($_GET['id'])) {
    $id_producto = $_GET['id'];
    $producto = $crud->obtenerProductoPorId($conexion, $id_producto);
} else {
    // Redirige si no se pasa un ID válido
    header("Location: productos.php");
    exit();
}

// Actualizar producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_producto'])) {
    $nombre_producto = $_POST['nombre_producto'];
    $precio = $_POST['precio'];
    $crud->actualizarProducto($conexion, $id_producto, $nombre_producto, $precio);

    // Redirige a la página de productos después de actualizar
    header("Location: productos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="css/estilo.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/productos.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php include_once 'encabezado.php'; ?>

    <div class="container">
        <h2>Editar Producto</h2>
        <form action="editar_producto.php?id=<?php echo $id_producto; ?>" method="POST" class="form-producto">
            <label for="nombre_producto">Nombre del Producto:</label>
            <input type="text" name="nombre_producto" value="<?php echo $producto['nombre_producto']; ?>" required>

            <label for="precio">Precio:</label>
            <input type="number" name="precio" value="<?php echo $producto['precio']; ?>" step="0.01" required>

            <button type="submit" name="actualizar_producto">Actualizar Producto</button>
        </form>
    </div>
</body>

</html>