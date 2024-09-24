<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ./index.html");
    exit();
}
include 'db_connection.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Error al cargar los datos del usuario.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/perfil.css">
</head>
<body>
    <header>
        <!-- Cargar el header aquí -->
        <div id="header-placeholder"></div>
    </header>
    <main class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($user['profile_image']); ?>" class="card-img-top" alt="Profile Picture">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($user['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <!-- Aquí puedes cargar más información del perfil del usuario -->
                <h1>Bienvenido, <?php echo htmlspecialchars($user['name']); ?></h1>
                <p>Esta es tu página de perfil.</p>
            </div>
        </div>
    </main>
    <footer>
        <!-- Cargar el footer aquí -->
        <div id="footer-placeholder"></div>
    </footer>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('./load_header.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('header-placeholder').innerHTML = data;
                });
            fetch('./load_footer.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('footer-placeholder').innerHTML = data;
                });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

