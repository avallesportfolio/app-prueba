<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    echo "Datos recibidos: Nombre = $nombre, Email = $email"; // Depuración

    // Validar que los campos no estén vacíos
    if (empty($nombre) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "Todos los campos son obligatorios.";
        exit();
    }

    // Validar que las contraseñas coinciden
    if ($password !== $confirm_password) {
        echo "Las contraseñas no coinciden.";
        exit();
    }

    // Validar si el correo ya está registrado
    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Error al preparar la consulta de verificación: " . $conn->error;
        exit();
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "El correo ya está registrado.";
        exit();
    }

    echo "El correo no está registrado, procediendo a registrar."; // Depuración

    // Insertar nuevo usuario en la base de datos
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Error al preparar la consulta de inserción: " . $conn->error;
        exit();
    }
    $stmt->bind_param("sss", $nombre, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "Usuario registrado correctamente."; // Depuración

        // Obtener el ID del usuario recién creado
        $user_id = $stmt->insert_id;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $nombre;
        $_SESSION['user_email'] = $email;
        header("Location: ./pages/perfil.html");
        exit();
    } else {
        echo "Error al registrar el usuario: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Solicitud no válida.";
}
?>
