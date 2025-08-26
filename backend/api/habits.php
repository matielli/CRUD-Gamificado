<?php
header("Content-Type: application/json");
require_once("../db.php");

$db = new Database();
$conn = $db->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":
        if (isset($_GET['id'])) {
            $stmt = $conn->prepare("SELECT * FROM habits WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            $stmt = $conn->query("SELECT * FROM habits");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;

    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO habits (user_id, title, description) VALUES (?, ?, ?)");
        $stmt->execute([$data['user_id'], $data['title'], $data['description']]);
        echo json_encode(["message" => "Hábito criado com sucesso!"]);
        break;

    case "PUT":
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("UPDATE habits SET title=?, description=? WHERE id=?");
        $stmt->execute([$data['title'], $data['description'], $data['id']]);
        echo json_encode(["message" => "Hábito atualizado com sucesso!"]);
        break;

    case "DELETE":
        $id = $_GET['id'] ?? null;
        if ($id) {
            $stmt = $conn->prepare("DELETE FROM habits WHERE id=?");
            $stmt->execute([$id]);
            echo json_encode(["message" => "Hábito deletado com sucesso!"]);
        }
        break;
}
