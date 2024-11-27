<?php
include("conexion.php");


// Insertar o editar empleado
if (isset($_POST['guardar_empleado'])) {
    $id_empleado = $_POST['id_empleado'] ?? null;
    $nombre = $_POST['nombre'];
    $puesto = $_POST['puesto'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $fecha_contratacion = $_POST['fecha_contratacion'];

    if ($id_empleado) {
        // Editar
        $stmt = $conexion->prepare("UPDATE empleados SET nombre_empleado = ?, puesto = ?, telefono = ?, email = ?, fecha_contratacion = ? WHERE id_empleado = ?");
        $stmt->bind_param("sssssi", $nombre, $puesto, $telefono, $email, $fecha_contratacion, $id_empleado);
    } else {
        // Insertar
        $stmt = $conexion->prepare("INSERT INTO empleados (nombre_empleado, puesto, telefono, email, fecha_contratacion) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nombre, $puesto, $telefono, $email, $fecha_contratacion);
    }
    $stmt->execute();
    echo "Empleado guardado con éxito.";
}

// Obtener empleados
$empleados = $conexion->query("SELECT * FROM empleados");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Empleados</title>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="css/productos.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php include_once 'encabezado.php'; ?>

    <h3>Gestionar empleados</h3>
    <form method="POST" class="form-producto">
        <input type="hidden" name="id_empleado" id="id_empleado">
        <label>Nombre:</label>
        <input type="text" name="nombre" required>
        <label>Puesto:</label>
        <input type="text" name="puesto" required>
        <label>Teléfono:</label>
        <input type="text" name="telefono">
        <label>Email:</label>
        <input type="email" name="email">
        <label>Fecha de Contratación:</label>
        <input type="date" name="fecha_contratacion" required>
        <button type="submit" name="guardar_empleado">Guardar</button>
    </form>

    <h3>Lista de Empleados</h3>
    <table id="tabla-productos">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Puesto</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Fecha de Contratación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($empleado = $empleados->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $empleado['nombre_empleado']; ?></td>
                    <td><?php echo $empleado['puesto']; ?></td>
                    <td><?php echo $empleado['telefono']; ?></td>
                    <td class="email"><?php echo $empleado['email']; ?></td>
                    <td><?php echo $empleado['fecha_contratacion']; ?></td>
                    <td>
                        <a href="editar_empleado.php?id_empleado=<?php echo $empleado['id_empleado']; ?>" class="btn-edit">Editar</a>
                        <a href="eliminar_empleado.php?id_empleado=<?php echo $empleado['id_empleado']; ?>" class="btn-delete">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>