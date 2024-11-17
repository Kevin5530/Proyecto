<?php
include 'conexion.php';
include_once 'CRUD.php';

$crud = new CRUD($conexion);

if (isset($_GET['id'])) {
    $id_cliente = $_GET['id'];
    $cliente = $crud->obtenerClientePorId($conexion, $id_cliente);
} else {
    // Redirige si no se pasa un ID válido
    header("Location: clientes.php");
    exit();
}

// Actualizar cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_cliente'])) {
    $nombre_cliente = $_POST['nombre_cliente'];
    $telefono = $_POST['telefono'];
    $crud->actualizarCliente($conexion, $id_cliente, $nombre_cliente, $telefono);

    // Redirige a la página de clientes después de actualizar
    header("Location: clientes.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="css/estilo.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php include_once 'encabezado.php'; ?>

    <div class="container">
        <h2>Editar Cliente</h2>
        <form action="editar_cliente.php?id=<?php echo $id_cliente; ?>" method="POST" class="form-producto">
            <label for="nombre_cliente">Nombre del Cliente:</label>
            <input type="text" id="nombre_cliente" name="nombre_cliente" value="<?php echo $cliente['nombre_cliente']; ?>" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo $cliente['telefono']; ?>">

            <button type="submit" name="actualizar_cliente">Actualizar Cliente</button>
        </form>
    </div>
</body>

</html>