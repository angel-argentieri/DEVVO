<?php
require_once '../CONFIG/db.php';
$database = new Database();
$db = $database->getConnection();
$stmt = $db->query("SELECT * FROM Usuarios");
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($resultado);
?>