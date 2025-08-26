<?php
header("Content-Type: application/json");
require_once("../db.php");

$db = new Database();
$conn = $db->getConnection();

if (isset($_GET['user_id'])) {
    $stmt = $conn->prepare("
        SELECT b.name, b.icon 
        FROM user_badges ub
        JOIN badges b ON ub.badge_id = b.id
        WHERE ub.user_id = ?
    ");
    $stmt->execute([$_GET['user_id']]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} else {
    $stmt = $conn->query("SELECT * FROM badges");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
