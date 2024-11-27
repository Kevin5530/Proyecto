<?php
class CRUD
{
    // 1. Agregar un nuevo cliente
    public function agregarCliente($conn, $nombre_cliente, $telefono = null, $num_personas)
    {
        $stmt = $conn->prepare("INSERT INTO clientes (nombre_cliente, telefono, num_personas) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nombre_cliente, $telefono, $num_personas);
        return $stmt->execute();
    }

    // 2. Ver todos los clientes
    public function obtenerClientes($conn)
    {
        $sql = "SELECT * FROM clientes";
        return $conn->query($sql);
    }

    // 3. Actualizar información de un cliente (nombre o teléfono en este caso)
    public function actualizarCliente($conn, $id_cliente, $nuevo_nombre_cliente, $nuevo_telefono, $num_personas)
    {
        $stmt = $conn->prepare("UPDATE clientes SET nombre_cliente = ?, telefono = ?, num_personas = ? WHERE id_cliente = ?");
        $stmt->bind_param("ssii", $nuevo_nombre_cliente, $nuevo_telefono, $num_personas,  $id_cliente);
        return $stmt->execute();
    }

    // Obtener un cliente específico por su ID
    public function obtenerClientePorId($conn, $cliente_id)
    {
        $stmt = $conn->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
        $stmt->bind_param("i", $cliente_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // 4. Eliminar un cliente
    public function eliminarCliente($conn, $id_cliente)
    {


        $stmt = $conn->prepare("DELETE FROM clientes WHERE id_cliente = ?");
        $stmt->bind_param("i", $id_cliente);
        return $stmt->execute();
    }


    // 1. Crear mesa
    public function agregarMesa($conn, $numero_mesa, $capacidad, $estado, $id_empleado)
    {
        $stmt = $conn->prepare("INSERT INTO control_mesas (numero_mesa, capacidad, estado, id_empleado) VALUES (?, ?, ?,?)");
        $stmt->bind_param("iisi", $numero_mesa, $capacidad, $estado, $id_empleado);
        return $stmt->execute();
    }

    // 2. Ver todas las mesas
    public function consultarMesas($conn)
    {
        $sql = "SELECT cm.id_mesa, cm.numero_mesa, cm.capacidad, cm.estado, e.nombre_empleado 
            FROM control_mesas cm
            LEFT JOIN empleados e ON cm.id_empleado = e.id_empleado";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function consultarMesasDisponibles($conn)
    {
        $sql = "SELECT id_mesa, numero_mesa, capacidad, estado FROM control_mesas WHERE estado = 'disponible'";
        return $conn->query($sql);
    }

    public function obtenerMesaPorId($conn, $id_mesa)
    {
        $stmt = $conn->prepare("SELECT * FROM control_mesas WHERE id_mesa = ?");
        $stmt->bind_param("i", $id_mesa);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // 3. Actualizar información de una mesa
    public function actualizarMesa($conn, $id_mesa, $nuevo_numero_mesa, $nuevo_capacidad, $id_empleado)
    {
        $stmt = $conn->prepare("UPDATE control_mesas SET numero_mesa = ?, capacidad = ?, id_empleado = ? WHERE id_mesa = ?");
        $stmt->bind_param("iiii", $nuevo_numero_mesa, $nuevo_capacidad, $id_empleado, $id_mesa);
        return $stmt->execute();
    }

    // 4. Eliminar una mesa
    public function eliminarMesa($conn, $id_mesa)
    {
        $stmt = $conn->prepare("DELETE FROM control_mesas WHERE id_mesa = ?");
        $stmt->bind_param("i", $id_mesa);
        return $stmt->execute();
    }

    public function buscarMesaDisponible($conn, $capacidad)
    {
        $stmt = $conn->prepare("SELECT * FROM control_mesas WHERE capacidad >= ? AND estado = 'disponible' LIMIT 1");
        $stmt->bind_param("i", $capacidad);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function asignarMesa($conn, $id_mesa, $id_cliente)
    {
        $conn->begin_transaction();

        $stmt = $conn->prepare("UPDATE control_mesas SET estado = 'ocupada' WHERE id_mesa = ?");
        $stmt->bind_param("i", $id_mesa);
        $stmt->execute();

        $conn->commit();
        return true;
    }

    public function liberarMesasPorCliente($conn, $id_cliente)
    {
        $stmt = $conn->prepare("UPDATE control_mesas SET estado = 'disponible' WHERE id_mesa = ?");
        $stmt->bind_param("i", $id_cliente);
        return $stmt->execute();
    }

    // 5. Agregar un nuevo producto
    public function agregarProducto($conn, $nombre_producto, $precio)
    {
        // No incluimos 'id_producto' ya que es AUTO_INCREMENT
        $stmt = $conn->prepare("INSERT INTO productos (nombre_producto, precio) VALUES (?, ?)");
        $stmt->bind_param("sd", $nombre_producto, $precio); // 's' para string, 'd' para decimal
        return $stmt->execute();
    }
    // 6. Ver todos los productos
    public function obtenerProductos($conn)
    {
        // Consulta para obtener todos los productos
        $sql = "SELECT id_producto, nombre_producto, precio FROM productos";
        return $conn->query($sql);
    }

    //6 obtener productos por medio de id_pedido
    public function obtenerProductosId($conn, $id_pedido)
    {
        $sql = "SELECT pr.id_producto, pr.nombre_producto, d.cantidad, d.subtotal
        FROM detalle_pedido d
        JOIN productos pr ON d.id_producto = pr.id_producto
        WHERE d.id_pedido = $id_pedido";
        $result = $conn->query($sql);
        return $result;
    }

    // 7. Actualizar información de un producto
    public function actualizarProducto($conn, $producto_id, $nombre_producto, $nuevo_precio)
    {
        $stmt = $conn->prepare("UPDATE productos SET nombre_producto = ?, precio = ? WHERE id_producto = ?");
        $stmt->bind_param("sdi", $nombre_producto, $nuevo_precio, $producto_id);
        return $stmt->execute();
    }


    // Obtener un producto específico por su ID
    public function obtenerProductoPorId($conn, $producto_id)
    {
        $stmt = $conn->prepare("SELECT * FROM productos WHERE id_producto = ?");
        $stmt->bind_param("i", $producto_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // 8. Eliminar un producto
    public function eliminarProducto($conn, $producto_id)
    {
        // Prepara la consulta para eliminar un producto
        $stmt = $conn->prepare("DELETE FROM productos WHERE id_producto = ?");
        $stmt->bind_param("i", $producto_id); // "i" para integer
        return $stmt->execute();
    }

    //ver empleados
    public function consultarEmpleados($conn)
    {
        $sql = "SELECT id_empleado, nombre_empleado FROM empleados";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    public function actualizarEmpleado($conn, $id_empleado, $nombre, $puesto, $telefono, $email, $fecha_contratacion)
    {
        $stmt = $conn->prepare("UPDATE empleados SET nombre_empleado = ?, puesto = ?, telefono = ?, email = ?, fecha_contratacion = ? WHERE id_empleado = ?");
        $stmt->bind_param("sssssi", $nombre, $puesto, $telefono, $email, $fecha_contratacion, $id_empleado);
        return $stmt->execute();
    }


    //  Eliminar un empleado
    public function eliminarEmpleado($conn, $id_empleado)
    {
        // Prepara la consulta para eliminar un producto
        $stmt = $conn->prepare("DELETE FROM empleados WHERE id_empleado = ?");
        $stmt->bind_param("i", $id_empleado); // "i" para integer
        return $stmt->execute();
    }



    // 9. Crear un nuevo pedido
    public function crearPedido($conn, $cliente_id, $total)
    {
        $stmt = $conn->prepare("INSERT INTO pedidos (cliente_id, total, fecha) VALUES (?, ?, NOW())");
        $stmt->bind_param("id", $cliente_id, $total);
        return $stmt->execute();
    }

    // 10. Ver todos los pedidos
    public function obtenerPedidos($conn)
    {
        $sql = "SELECT p.id_pedido, c.nombre_cliente, p.fecha_pedido, p.total
        FROM pedido p
        JOIN clientes c ON p.id_cliente = c.id_cliente
        ORDER BY p.fecha_pedido DESC";
        $result = $conn->query($sql);
        return $result;
    }

    // 11. Ver pedidos de un cliente específico
    public function obtenerPedidosCliente($conn, $cliente_id)
    {
        $stmt = $conn->prepare("SELECT * FROM pedidos WHERE cliente_id = ?");
        $stmt->bind_param("i", $cliente_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // 12. Actualizar el total de un pedido
    public function actualizarTotalPedido($conn, $pedido_id, $nuevo_total)
    {
        $stmt = $conn->prepare("UPDATE pedidos SET total = ? WHERE pedido_id = ?");
        $stmt->bind_param("di", $nuevo_total, $pedido_id);
        return $stmt->execute();
    }

    // 13. Eliminar un pedido
    public function eliminarPedido($conn, $id_pedido)
    {
        // Eliminar primero los datos relacionados en la tabla cocina
        $stmt_cocina = $conn->prepare("DELETE FROM cocina WHERE id_pedido = ?");
        $stmt_cocina->bind_param("i", $id_pedido);
        if (!$stmt_cocina->execute()) {
            throw new Exception("Error al eliminar registros en cocina: " . $stmt_cocina->error);
        }

        // Luego eliminar el pedido
        $stmt_pedido = $conn->prepare("DELETE FROM pedido WHERE id_pedido = ?");
        $stmt_pedido->bind_param("i", $id_pedido);
        if (!$stmt_pedido->execute()) {
            throw new Exception("Error al eliminar el pedido: " . $stmt_pedido->error);
        }

        return true;
    }

    // 14. Agregar un producto a un pedido (detalle del pedido)
    public function agregarProductoPedido($conn, $pedido_id, $producto_id, $cantidad, $precio)
    {
        $stmt = $conn->prepare("INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $pedido_id, $producto_id, $cantidad, $precio);
        return $stmt->execute();
    }

    // 15. Ver detalles de un pedido
    public function obtenerDetallePedido($conn, $pedido_id)
    {
        $stmt = $conn->prepare("SELECT * FROM detalle_pedidos WHERE pedido_id = ?");
        $stmt->bind_param("i", $pedido_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // 15. Ver detalles de un pedido por id de cliente
    public function obtenerDetallePedidoId($conn, $id_pedido)
    {
        $sql = "SELECT p.id_pedido, c.nombre_cliente, p.fecha_pedido, p.total
        FROM pedido p
        JOIN clientes c ON p.id_cliente = c.id_cliente
        WHERE p.id_pedido = $id_pedido";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }



    // 16. Calcular el total de ventas del día
    public function totalVentasDia($conn)
    {
        $sql = "SELECT SUM(total) AS total_ventas_dia FROM pedidos WHERE DATE(fecha) = CURDATE()";
        $result = $conn->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }

    // Obtener pedidos en cocina junto con productos
    public function obtenerPedidosProductosCocina($conn)
    {
        $sql = "SELECT 
        c.id_cocina, 
        c.id_pedido, 
        p.fecha_pedido, 
        c.tiempo_preparacion, 
        c.estado, 
        pr.nombre_producto, 
        c.cantidad
        FROM cocina c
        JOIN pedido p ON c.id_pedido = p.id_pedido
        JOIN productos pr ON c.id_producto = pr.id_producto
        WHERE c.estado != 'Listo'
        ORDER BY c.id_cocina ASC";
        $result = $conn->query($sql);
        return $result;
    }

    //envia productos a cocina
    public function enviarPedidoACocina($conn, $id_pedido)
    {
        // Obtener los productos del pedido
        $productos = $conn->query("
            SELECT dp.id_producto, dp.cantidad
            FROM detalle_pedido dp
            WHERE dp.id_pedido = $id_pedido
        ");

        if (!$productos) {
            echo "Error al obtener productos: " . $conn->error;
            return false;
        }

        while ($producto = $productos->fetch_assoc()) {
            $id_producto = $producto['id_producto'];
            $cantidad = $producto['cantidad'];

            // Verificar si ya existe el producto en la tabla cocina
            $verificar = $conn->query("
                SELECT id_cocina 
                FROM cocina 
                WHERE id_pedido = $id_pedido AND id_producto = $id_producto
            ");

            if (!$verificar) {
                echo "Error al verificar existencia en cocina: " . $conn->error;
                return false;
            }

            if ($verificar->num_rows === 0) {
                // Insertar el producto en la tabla cocina
                $stmt = $conn->prepare("
                    INSERT INTO cocina (id_pedido, id_producto, cantidad, tiempo_preparacion, estado) 
                    VALUES (?, ?, ?, NULL, 'Pendiente')
                ");

                if (!$stmt) {
                    echo "Error al preparar la consulta: " . $conn->error;
                    return false;
                }

                $stmt->bind_param("iii", $id_pedido, $id_producto, $cantidad);

                if (!$stmt->execute()) {
                    echo "Error al insertar producto $id_producto: " . $stmt->error;
                    return false;
                } else {
                    echo "Producto $id_producto del pedido $id_pedido insertado correctamente en cocina.<br>";
                }
            } else {
                echo "Producto $id_producto ya existe en cocina para el pedido $id_pedido.<br>";
            }
        }
        return true;
    }




    // 17. Consultar los productos más vendidos
    public function productosMasVendidos($conn, $limite = 5)
    {
        $sql = "
        SELECT prod.nombre, SUM(d.cantidad) AS total_vendido
        FROM detalle_pedidos d
        JOIN productos prod ON d.producto_id = prod.producto_id
        GROUP BY d.producto_id
        ORDER BY total_vendido DESC
        LIMIT $limite";
        return $conn->query($sql);
    }

    // Crear una nueva factura
    public function crearFactura($conn, $id_pedido, $id_cliente, $total, $fecha)
    {
        $stmt = $conn->prepare("INSERT INTO factura (id_pedido, id_cliente, total, fecha) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iids", $id_pedido, $id_cliente, $total, $fecha);
        return $stmt->execute();
    }

    // Obtener todas las facturas
    public function obtenerFacturas($conn)
    {
        $sql = "SELECT f.id_factura, c.nombre_cliente, f.total, f.fecha
                FROM factura f
                JOIN clientes c ON f.id_cliente = c.id_cliente
                ORDER BY f.fecha DESC";
        $result = $conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Obtener una factura específica por ID
    public function obtenerFacturaPorId($conn, $id_factura)
    {
        $stmt = $conn->prepare("SELECT * FROM factura WHERE id_factura = ?");
        $stmt->bind_param("i", $id_factura);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Actualizar una factura
    public function actualizarFactura($conn, $id_factura, $nuevo_total, $nueva_fecha)
    {
        $stmt = $conn->prepare("UPDATE factura SET total = ?, fecha = ? WHERE id_factura = ?");
        $stmt->bind_param("dsi", $nuevo_total, $nueva_fecha, $id_factura);
        return $stmt->execute();
    }

    // Eliminar una factura
    public function eliminarFactura($conn, $id_factura)
    {
        $stmt = $conn->prepare("DELETE FROM factura WHERE id_factura = ?");
        $stmt->bind_param("i", $id_factura);
        return $stmt->execute();
    }

    // Obtener facturas de un cliente específico
    public function obtenerFacturasCliente($conn, $id_cliente)
    {
        $stmt = $conn->prepare("SELECT * FROM factura WHERE id_cliente = ?");
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // 18. Ver el pedido con el total más alto
    public function pedidoConTotalMasAlto($conn)
    {
        $sql = "SELECT * FROM pedidos ORDER BY total DESC LIMIT 1";
        $result = $conn->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }

    // Obtener facturas de un pedido específico
    public function obtenerFacturasPedido($conn, $id_pedido)
    {
        $stmt = $conn->prepare("SELECT * FROM factura WHERE id_pedido = ?");
        $stmt->bind_param("i", $id_pedido);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Calcular el total facturado en un período de tiempo
    public function calcularTotalFacturado($conn, $fecha_inicio, $fecha_fin)
    {
        $stmt = $conn->prepare("SELECT SUM(total) AS total_facturado FROM factura WHERE fecha BETWEEN ? AND ?");
        $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function obtenerFacturaPorPedido($conexion, $id_pedido)
    {
        $sql = "SELECT * FROM factura WHERE id_pedido = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $id_pedido);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function marcarComoPagado($conexion, $id_pedido)
    {
        $sql = "UPDATE pedido SET estado = 'pagado' WHERE id_pedido = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $id_pedido);
        return $stmt->execute();
    }
}
