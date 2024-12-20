<?php
include 'conexion.php';
include_once 'CRUD.php';

$crud = new CRUD($conexion);

// Procesar el formulario

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar_mesa'])) {
    $numero_mesa = isset($_POST['numero_mesa']) ? (int) $_POST['numero_mesa'] : null;
    $capacidad = isset($_POST['capacidad']) ? (int) $_POST['capacidad'] : null;
    $estado = isset($_POST['estado']) ? $_POST['estado'] : 'disponible';
    $id_empleado = isset($_POST['id_empleado']) ? (int) $_POST['id_empleado'] : null;

    $crud->agregarMesa($conexion, $numero_mesa, $capacidad, $estado, $id_empleado);

    // Redirige para evitar reenvío del formulario
    header("Location: control_mesas.php");
    exit();
}

if (isset($_GET['eliminar'])) {
    $id_mesa = $_GET['eliminar'];

    // Llamar a la función eliminarCliente en la clase CRUD
    if ($crud->eliminarMesa($conexion, $id_mesa)) {
        header("Location: control_mesas.php");
        exit();
    } else {
        echo "<p style='color: red; text-align: center;'>Error al eliminar la mesa.</p>";
    }
}

// Consultar las mesas
$mesas = $crud->consultarMesas($conexion);
$empleados = $crud->consultarEmpleados($conexion); // Lista de empleados para el formulario
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Mesas</title>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="css/productos.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php include_once 'encabezado.php'; ?>
    <h2>Gestión de Mesas</h2>

    <form method="POST" class="form-producto">
        <label for="numero_mesa">Número de Mesa:</label>
        <input type="number" id="numero_mesa" name="numero_mesa" required placeholder="Ej. 1">

        <label for="capacidad">Capacidad:</label>
        <input type="number" id="capacidad" name="capacidad" required placeholder="Ej. 4">

        <label for="id_empleado">Mesero:</label>
        <select id="id_empleado" name="id_empleado" required>
            <?php foreach ($empleados as $empleado): ?>
                <option value="<?php echo $empleado['id_empleado']; ?>">
                    <?php echo $empleado['nombre_empleado']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" name="agregar_mesa">Agregar Mesa</button>
    </form>

    <h3>Lista de mesas</h3>
    <div id="tabla-productos">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Número de Mesa</th>
                    <th>Capacidad</th>
                    <th>Estado</th>
                    <th>Mesero</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($mesas)): ?>
                    <?php foreach ($mesas as $mesa): ?>
                        <tr>
                            <td><?php echo $mesa['id_mesa']; ?></td>
                            <td><?php echo $mesa['numero_mesa']; ?></td>
                            <td><?php echo $mesa['capacidad']; ?></td>
                            <td><?php echo $mesa['estado'] === 'disponible' ? 'Disponible' : 'Ocupada'; ?></td>
                            <td><?php echo $mesa['nombre_empleado'] ?? 'Sin asignar'; ?></td>
                            <td>
                                <a href="editar_mesa.php?id=<?php echo $mesa['id_mesa']; ?>">Editar</a>
                                <a href="control_mesas.php?eliminar=<?php echo $mesa['id_mesa']; ?>">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">No hay mesas .</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
</body>

</html>