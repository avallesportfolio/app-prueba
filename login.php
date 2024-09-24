<?php
// login.php
session_start();
require_once 'db_connection.php'; // Asegúrate de que este archivo exista y contenga la conexión a la base de datos

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!$email || !$password) {
        echo json_encode(["success" => false, "message" => "Por favor, proporciona un email y contraseña"]);
        exit;
    }

    // Preparar la consulta
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        echo json_encode([
            "success" => true, 
            "message" => "Inicio de sesión exitoso",
            "user" => [
                "id" => $user['id'],
                "name" => $user['name'],
                "email" => $user['email']
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Email o contraseña inválidos"]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
}

$conn->close();
?>