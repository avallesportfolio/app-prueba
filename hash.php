<?php
require_once 'db_connection.php';

$stmt = $conn->prepare("SELECT id, password FROM users");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $password = $row['password'];
    
    // Solo actualiza si la contraseña no está ya hasheada con password_hash
    if (!password_get_info($password)['algo']) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updateStmt->bind_param("si", $hashedPassword, $id);
        $updateStmt->execute();
        $updateStmt->close();
        
        echo "Updated password for user ID: $id\n";
    }
}

$stmt->close();
$conn->close();

echo "Password update process completed.\n";
?>