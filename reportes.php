<?php
// reportes.php
include 'conexion.php';
include_once 'CRUD.php';

$crud = new CRUD();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes - Restaurante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Reportes del Restaurante</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Ventas Diarias
                </div>
                <div class="card-body">
                    <form method="GET" action="">
                        <input type="date" name="fecha_ventas" class="form-control mb-2" required>
                        <button type="submit" name="reporte_ventas_diarias" class="btn btn-primary">
                            Generar Reporte
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Ventas por Rango de Fechas
                </div>
                <div class="card-body">
                    <form method="GET" action="">
                        <input type="date" name="fecha_inicio" class="form-control mb-2" required>
                        <input type="date" name="fecha_fin" class="form-control mb-2" required>
                        <button type="submit" name="reporte_ventas_rango" class="btn btn-primary">
                            Generar Reporte
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Productos Más Vendidos
                </div>
                <div class="card-body">
                    <form method="GET" action="">
                        <select name="limite" class="form-control mb-2">
                            <option value="5">Top 5</option>
                            <option value="10">Top 10</option>
                            <option value="20">Top 20</option>
                        </select>
                        <button type="submit" name="reporte_productos_vendidos" class="btn btn-primary">
                            Generar Reporte
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    // Procesar Reportes
    if (isset($_GET['reporte_ventas_diarias'])) {
        $fecha = $_GET['fecha_ventas'];
        $sql = "SELECT 
                    COUNT(*) as total_pedidos, 
                    SUM(total) as total_ventas 
                FROM pedidos 
                WHERE DATE(fecha) = '$fecha'";
        $resultado = $conexion->query($sql);
        $datos = $resultado->fetch_assoc();
        ?>
        <div class="card mt-4">
            <div class="card-header">
                Reporte de Ventas - <?php echo $fecha; ?>
            </div>
            <div class="card-body">
                <p>Total Pedidos: <?php echo $datos['total_pedidos']; ?></p>
                <p>Total Ventas: $<?php echo number_format($datos['total_ventas'], 2); ?></p>
            </div>
        </div>
        <?php
    }

    if (isset($_GET['reporte_ventas_rango'])) {
        $fecha_inicio = $_GET['fecha_inicio'];
        $fecha_fin = $_GET['fecha_fin'];
        $sql = "SELECT 
                    DATE(fecha) as fecha_venta, 
                    COUNT(*) as total_pedidos, 
                    SUM(total) as total_ventas 
                FROM pedidos 
                WHERE fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' 
                GROUP BY DATE(fecha)";
        $resultado = $conexion->query($sql);
        ?>
        <div class="card mt-4">
            <div class="card-header">
                Reporte de Ventas - <?php echo "$fecha_inicio a $fecha_fin"; ?>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Pedidos</th>
                            <th>Ventas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_general = 0;
                        while ($fila = $resultado->fetch_assoc()) { 
                            $total_general += $fila['total_ventas'];
                        ?>
                        <tr>
                            <td><?php echo $fila['fecha_venta']; ?></td>
                            <td><?php echo $fila['total_pedidos']; ?></td>
                            <td>$<?php echo number_format($fila['total_ventas'], 2); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>TOTAL</th>
                            <th></th>
                            <th>$<?php echo number_format($total_general, 2); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <?php
    }

    if (isset($_GET['reporte_productos_vendidos'])) {
        $limite = $_GET['limite'];
        $sql = "SELECT 
                    p.nombre_producto, 
                    SUM(dp.cantidad) as total_vendido, 
                    SUM(dp.cantidad * dp.precio) as total_ingresos
                FROM detalle_pedidos dp
                JOIN productos p ON dp.producto_id = p.id_producto
                GROUP BY p.id_producto
                ORDER BY total_vendido DESC
                LIMIT $limite";
        $resultado = $conexion->query($sql);
        ?>
        <div class="card mt-4">
            <div class="card-header">
                Top <?php echo $limite; ?> Productos Más Vendidos
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad Vendida</th>
                            <th>Ingresos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $resultado->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $fila['nombre_producto']; ?></td>
                            <td><?php echo $fila['total_vendido']; ?></td>
                            <td>$<?php echo number_format($fila['total_ingresos'], 2); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>