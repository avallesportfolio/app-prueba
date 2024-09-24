<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if ($user) {
        // Obtener reservas del usuario
        $stmt = $pdo->prepare("SELECT r.*, p.name as property_name FROM reservations r JOIN properties p ON r.property_id = p.id WHERE r.user_id = ?");
        $stmt->execute([$user_id]);
        $reservations = $stmt->fetchAll();

        // Obtener comentarios del usuario
        $stmt = $pdo->prepare("SELECT r.*, p.name as property_name FROM reviews r JOIN properties p ON r.property_id = p.id WHERE r.user_id = ?");
        $stmt->execute([$user_id]);
        $reviews = $stmt->fetchAll();

        echo json_encode([
            "success" => true,
            "user" => $user,
            "reservations" => $reservations,
            "reviews" => $reviews
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "User not found"]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}