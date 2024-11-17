<?php
class CRUD
{
    // 1. Agregar un nuevo cliente
    public function agregarCliente($conn, $nombre_cliente, $telefono = null)
    {
        $stmt = $conn->prepare("INSERT INTO clientes (nombre_cliente, telefono) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre_cliente, $telefono);
        return $stmt->execute();
    }

    // 2. Ver todos los clientes
    public function obtenerClientes($conn)
    {
        $sql = "SELECT * FROM clientes";
        return $conn->query($sql);
    }

    // 3. Actualizar información de un cliente (nombre o teléfono en este caso)
    public function actualizarCliente($conn, $id_cliente, $nuevo_nombre_cliente, $nuevo_telefono)
    {
        $stmt = $conn->prepare("UPDATE clientes SET nombre_cliente = ?, telefono = ? WHERE id_cliente = ?");
        $stmt->bind_param("ssi", $nuevo_nombre_cliente, $nuevo_telefono, $id_cliente);
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
        $sql = "SELECT * FROM pedidos";
        return $conn->query($sql);
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
    public function eliminarPedido($conn, $pedido_id)
    {
        $stmt = $conn->prepare("DELETE FROM pedidos WHERE pedido_id = ?");
        $stmt->bind_param("i", $pedido_id);
        return $stmt->execute();
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

    // 16. Calcular el total de ventas del día
    public function totalVentasDia($conn)
    {
        $sql = "SELECT SUM(total) AS total_ventas_dia FROM pedidos WHERE DATE(fecha) = CURDATE()";
        $result = $conn->query($sql);
        return $result ? $result->fetch_assoc() : null;
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

    // 18. Ver el pedido con el total más alto
    public function pedidoConTotalMasAlto($conn)
    {
        $sql = "SELECT * FROM pedidos ORDER BY total DESC LIMIT 1";
        $result = $conn->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }
}
