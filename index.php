<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Restaurante</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>

<body>

    <?php include_once 'encabezado.php'; ?>

    <main>
        <?php
        include_once 'CRUD.php';
        include 'conexion.php';
        // Determinamos qué sección se va a mostrar
        $seccion = $_GET['seccion'] ?? 'inicio';

        switch ($seccion) {
            case 'clientes':
                include 'clientes.php';
                break;
            case 'productos':
                include 'productos.php';
                break;
            case 'pedidos':
                include 'pedidos.php';
                break;
            case 'reportes':
                include 'reportes.php';
                break;
            case 'control de mesas':
                include 'control_mesas.php';
                break;
            case 'factura';
                include 'factura.php';
                break;
            case 'cocina':
                include 'cocina.php';
                break;
            default:
                break;
        }
        ?>
    </main>

    <footer>
        <p>&copy; 2024 Gestión de Restaurante</p>
    </footer>
</body>

</html>