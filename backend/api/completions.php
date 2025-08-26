<?php
header("Content-Type: application/json");
require_once("../db.php");
require_once("../gamification.php");

$db = new Database();
$conn = $db->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":
        $stmt = $conn->query("
            SELECT c.id, u.name as user, h.title as habit, c.date 
            FROM completions c
            JOIN users u ON c.user_id = u.id
            JOIN habits h ON c.habit_id = h.id
        ");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO completions (user_id, habit_id, date) VALUES (?, ?, NOW())");
        $stmt->execute([$data['user_id'], $data['habit_id']]);

        // aplica gamificação
        applyGamification($conn, $data['user_id'], $data['habit_id']);

        echo json_encode(["message" => "Hábito concluído e pontos adicionados!"]);
        break;
}
