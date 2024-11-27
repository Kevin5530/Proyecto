<?php
include("conexion.php");
include_once 'CRUD.php';

$crud = new CRUD($conexion);

// Obtener pedidos en cocina junto con productos
$pedidos = $crud->obtenerPedidosProductosCocina($conexion);

if (!$pedidos) {
    die("Error en la consulta: " . $conexion->error);
}

// Marcar pedido como listo
if (isset($_POST['marcar_listo'])) {
    $id_cocina = $_POST['id_cocina'];
    $stmt = $conexion->prepare("UPDATE cocina SET estado = 'Listo' WHERE id_cocina = ?");
    $stmt->bind_param("i", $id_cocina);

    if ($stmt->execute()) {
        header("Location: cocina.php");
        exit();
    } else {
        echo "<p style='color: red;'>Error al actualizar el estado: " . $stmt->error . "</p>";
    }
}

// Asignar tiempo de preparación
if (isset($_POST['asignar_tiempo'])) {
    $id_cocina = $_POST['id_cocina'];
    $tiempo_preparacion = $_POST['tiempo_preparacion'];

    $stmt = $conexion->prepare("UPDATE cocina SET tiempo_preparacion = ?, estado = 'En preparación' WHERE id_cocina = ?");
    $stmt->bind_param("si", $tiempo_preparacion, $id_cocina);

    if ($stmt->execute()) {
        header("Location: cocina.php");
        exit();
    } else {
        echo "<p style='color: red;'>Error al asignar el tiempo de preparación: " . $stmt->error . "</p>";
    }
}



?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Cocina</title>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="css/productos.css?v=<?php echo time(); ?>">

    <script>
        function iniciarContadores() {
            const filas = document.querySelectorAll("[data-tiempo]");
            filas.forEach(fila => {
                const tiempoInicial = fila.dataset.tiempo; // Tiempo en formato HH:MM:SS
                const tiempoElemento = fila.querySelector(".contador");

                if (!tiempoInicial || tiempoInicial === "00:00:00") {
                    tiempoElemento.textContent = "Sin tiempo asignado";
                    return;
                }

                let [horas, minutos, segundos] = tiempoInicial.split(":").map(Number);
                const idCocina = fila.dataset.idCocina; // ID de cocina

                const intervalo = setInterval(() => {
                    if (horas === 0 && minutos === 0 && segundos === 0) {
                        clearInterval(intervalo);
                        tiempoElemento.textContent = "¡Listo!";
                        fila.querySelector(".estado").textContent = "Listo";

                        // Enviar actualización al servidor
                        fetch('actualizar_estado.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    id_cocina: idCocina
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    console.log(`Pedido ${idCocina} actualizado a Listo.`);
                                } else {
                                    console.error(`Error al actualizar el pedido ${idCocina}:`, data.error);
                                }
                            })
                            .catch(error => console.error('Error en la actualización:', error));

                        return;
                    }

                    if (segundos === 0) {
                        segundos = 59;
                        if (minutos === 0) {
                            minutos = 59;
                            horas--;
                        } else {
                            minutos--;
                        }
                    } else {
                        segundos--;
                    }

                    tiempoElemento.textContent = `${String(horas).padStart(2, "0")}:${String(minutos).padStart(2, "0")}:${String(segundos).padStart(2, "0")}`;
                }, 1000);
            });
        }

        document.addEventListener("DOMContentLoaded", iniciarContadores);
    </script>
</head>

<body>
    <?php include_once 'encabezado.php'; ?>

    <h2>Gestión de Cocina</h2>

    <table id="tabla-productos">
        <thead>
            <tr>
                <th>ID Cocina</th>
                <th>ID Pedido</th>
                <th>Fecha Pedido</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Tiempo de Preparación</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($pedido = $pedidos->fetch_assoc()): ?>
                <tr data-tiempo="<?php echo $pedido['tiempo_preparacion']; ?>" data-id-cocina="<?php echo $pedido['id_cocina']; ?>">
                    <td><?php echo $pedido['id_cocina']; ?></td>
                    <td><?php echo $pedido['id_pedido']; ?></td>
                    <td><?php echo $pedido['fecha_pedido']; ?></td>
                    <td><?php echo $pedido['nombre_producto']; ?></td>
                    <td><?php echo $pedido['cantidad']; ?></td>
                    <td class="contador">
                        <?php echo $pedido['tiempo_preparacion'] ?: "Sin tiempo asignado"; ?>
                    </td>
                    <td class="estado"><?php echo $pedido['estado']; ?></td>
                    <td>
                        <?php if ($pedido['estado'] == 'Pendiente'): ?>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="id_cocina" value="<?php echo $pedido['id_cocina']; ?>">
                                <input type="text" name="tiempo_preparacion" placeholder="Tiempo (HH:MM:SS)" required>
                                <button type="submit" name="asignar_tiempo">Asignar Tiempo</button>
                            </form>
                        <?php elseif ($pedido['estado'] == 'En preparación'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id_cocina" value="<?php echo $pedido['id_cocina']; ?>">
                                <button type="submit" name="marcar_listo">Marcar como Listo</button>
                            </form>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>