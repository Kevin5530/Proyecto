<?php
include 'conexion.php';
include_once 'CRUD.php';

$crud = new CRUD($conexion);

if (isset($_GET['id'])) {
    $id_mesa = $_GET['id'];
    $mesa = $crud->obtenerMesaPorId($conexion, $id_mesa);
} else {
    // Redirige si no se pasa un ID válido
    header("Location: control_mesas.php");
    exit();
}

// Actualizar producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_mesa'])) {
    $numero_mesa = isset($_POST['numero_mesa']) ? (int) $_POST['numero_mesa'] : null;
    $capacidad = isset($_POST['capacidad']) ? (int) $_POST['capacidad'] : null;

    $crud->actualizarMesa($conexion, $id_mesa, $numero_mesa, $capacidad);

    // Redirige a la página de productos después de actualizar
    header("Location: control_mesas.php");
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
</head>

<body>
    <?php include_once 'encabezado.php'; ?>

    <div class="container">
        <h2>Editar Mesa</h2>
        <form action="editar_mesa.php?id=<?php echo $id_mesa; ?>" method="POST" class="form-producto">
            <label for="numero_mesa">Número de Mesa:</label>
            <input type="number" id="numero_mesa" name="numero_mesa" value="<?php echo $mesa['numero_mesa']; ?>" required>

            <label for="capacidad">Capacidad:</label>
            <input type="number" id="capacidad" name="capacidad" value="<?php echo $mesa['numero_mesa']; ?>" required>

            <button type="submit" name="actualizar_mesa">Actualizar Mesa</button>
        </form>
    </div>
</body>

</html>