<?php
header("Content-Type: application/json");
require_once("../db.php");

$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->query("SELECT id, name, points FROM users ORDER BY points DESC");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
