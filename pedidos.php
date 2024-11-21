<?php
include 'conexion.php';
include_once 'CRUD.php';

$crud = new CRUD($conexion);

// Procesar formulario para agregar pedido
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar_pedido'])) {
    $id_cliente = $_POST['id_cliente'];
    $id_mesa = $_POST['id_mesa'];
}

// Procesar eliminación de un pedido
if (isset($_GET['eliminar'])) {
    $id_pedido = $_GET['eliminar'];

    if ($crud->eliminarPedido($conexion, $id_pedido)) {
        echo "<p style='color: green;'>Pedido eliminado exitosamente.</p>";
    } else {
        echo "<p style='color: red;'>Error al eliminar el pedido.</p>";
    }
}

// Consultar pedidos
$pedidos = $crud->obtenerPedidos($conexion);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>

<body>
    <?php include_once 'encabezado.php'; ?>

    <h2>Gestión de Pedidos</h2>

    <!-- Formulario para agregar pedido -->
    <form method="POST" action="pedidos.php">
        <label for="id_cliente">ID Cliente:</label>
        <input type="number" id="id_cliente" name="id_cliente" required placeholder="Ej. 1">

        <label for="id_mesa">ID Mesa:</label>
        <input type="number" id="id_mesa" name="id_mesa" required placeholder="Ej. 1">

        <button type="submit" name="agregar_pedido">Agregar Pedido</button>
    </form>

    <!-- Lista de pedidos -->
    <h3>Lista de Pedidos</h3>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>ID Cliente</th>
                    <th>ID Mesa</th>
                    <th>Fecha y Hora</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pedidos)): ?>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?php echo $pedido['id_pedido']; ?></td>
                            <td><?php echo $pedido['id_cliente']; ?></td>
                            <td><?php echo $pedido['id_mesa']; ?></td>
                            <td><?php echo $pedido['fecha_hora']; ?></td>
                            <td>
                                <a href="pedidos.php?eliminar=<?php echo $pedido['id_pedido']; ?>">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No hay pedidos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>