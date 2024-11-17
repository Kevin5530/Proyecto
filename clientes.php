<?php
include 'conexion.php';
include_once 'CRUD.php';

$crud = new CRUD($conexion);

// Procesar formulario de agregar cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar_cliente'])) {
    $nombre = $_POST['nombre'];
    // Si el teléfono no se proporciona, se deja como NULL
    $telefono = isset($_POST['telefono']) && !empty($_POST['telefono']) ? $_POST['telefono'] : null;
    $crud->agregarCliente($conexion, $nombre, $telefono);

    // Redirige para evitar reenvío del formulario
    header("Location: clientes.php");
    exit();
}

// Procesar eliminación de cliente
if (isset($_GET['eliminar'])) {
    $id_cliente = $_GET['eliminar'];

    // Llamar a la función eliminarCliente en la clase CRUD
    if ($crud->eliminarCliente($conexion, $id_cliente)) {
        header("Location: clientes.php");
        exit();
    } else {
        echo "<p style='color: red; text-align: center;'>Error al eliminar el cliente.</p>";
    }
}

// Obtener lista de clientes
$clientes = $crud->obtenerClientes($conexion);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>

<body>
    <?php include_once 'encabezado.php'; ?>

    <h2>Clientes</h2>

    <h3>Agregar Cliente</h3>
    <form method="POST" action="clientes.php">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" placeholder="Ej. Juan Pérez" required>

        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" placeholder="Ej. 3334567890">

        <button type="submit" name="agregar_cliente">Agregar Cliente</button>
    </form>

    <h3>Lista de Clientes</h3>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($cliente = $clientes->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $cliente['id_cliente']; ?></td>
                        <td><?php echo $cliente['nombre_cliente']; ?></td>
                        <td><?php echo $cliente['telefono']; ?></td>
                        <td>
                            <a href="editar_cliente.php?id=<?php echo $cliente['id_cliente']; ?>" class="btn-edit">Editar</a>
                            <a href="clientes.php?eliminar=<?php echo $cliente['id_cliente']; ?>" class="btn-delete">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>