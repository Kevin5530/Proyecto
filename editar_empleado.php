<?php
include 'conexion.php';
include_once 'CRUD.php';

$crud = new CRUD($conexion);

if (isset($_GET['id_empleado'])) {
    $id_empleado = $_GET['id_empleado'];
    // Obtener los datos del empleado
    $sql = "SELECT * FROM empleados WHERE id_empleado = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_empleado);
    $stmt->execute();
    $result = $stmt->get_result();
    $empleado = $result->fetch_assoc();

    if (!$empleado) {
        // Redirige si no se encuentra el empleado
        header("Location: empleados.php");
        exit();
    }
} else {
    // Redirige si no se pasa un ID válido
    header("Location: empleados.php");
    exit();
}

// Actualizar empleado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_empleado'])) {
    $nombre = $_POST['nombre'];
    $puesto = $_POST['puesto'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $fecha_contratacion = $_POST['fecha_contratacion'];

    $crud->actualizarEmpleado($conexion, $id_empleado, $nombre, $puesto, $telefono, $email, $fecha_contratacion);

    // Redirige a la lista de empleados después de actualizar
    header("Location: empleados.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado</title>
    <link rel="stylesheet" href="css/estilo.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php include_once 'encabezado.php'; ?>

    <div class="container">
        <h2>Editar Empleado</h2>
        <form action="editar_empleado.php?id_empleado=<?php echo $id_empleado; ?>" method="POST" class="form-producto">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="<?php echo $empleado['nombre_empleado']; ?>" required>

            <label for="puesto">Puesto:</label>
            <input type="text" name="puesto" value="<?php echo $empleado['puesto']; ?>" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" value="<?php echo $empleado['telefono']; ?>">

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $empleado['email']; ?>">

            <label for="fecha_contratacion">Fecha de Contratación:</label>
            <input type="date" name="fecha_contratacion" value="<?php echo $empleado['fecha_contratacion']; ?>" required>

            <button type="submit" name="actualizar_empleado">Actualizar Empleado</button>
        </form>
    </div>
</body>

</html>