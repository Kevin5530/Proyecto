<?php
include("conexion.php");

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id_cocina'])) {
    $id_cocina = $data['id_cocina'];

    $stmt = $conexion->prepare("UPDATE cocina SET estado = 'Listo' WHERE id_cocina = ?");
    $stmt->bind_param("i", $id_cocina);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "ID de cocina no proporcionado."]);
}
