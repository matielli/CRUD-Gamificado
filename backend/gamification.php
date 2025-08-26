<?php
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/config.php";

class Gamification {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

  
    public function addPoints($userId, $points) {
        $stmt = $this->db->prepare("UPDATE users SET points = points + ? WHERE id = ?");
        $stmt->execute([$points, $userId]);
        $this->checkBadges($userId);
    }

  
    public function checkBadges($userId) {
        $stmt = $this->db->prepare("SELECT points FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $points = $stmt->fetchColumn();

        $stmt = $this->db->prepare("
            SELECT id FROM badges 
            WHERE points_required <= ? 
            AND id NOT IN (SELECT badge_id FROM user_badges WHERE user_id = ?)
        ");
        $stmt->execute([$points, $userId]);
        $badges = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($badges as $badgeId) {
            $stmt = $this->db->prepare("INSERT INTO user_badges (user_id, badge_id) VALUES (?, ?)");
            $stmt->execute([$userId, $badgeId]);
        }
    }

  
    public function getRanking() {
        $stmt = $this->db->query("SELECT id, name, points FROM users ORDER BY points DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
