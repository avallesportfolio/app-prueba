<?php
session_start();
include 'db_connection.php';

$is_authenticated = isset($_SESSION['user_id']);
$user = null;
$profile_image = null;

if ($is_authenticated) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    }

    // Obtener imagen de perfil del usuario
    $sql = "SELECT image FROM user_images WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $image_result = $stmt->get_result();
    if ($image_result->num_rows > 0) {
        $profile_image = $image_result->fetch_assoc()['image'];
    }
    $stmt->close();
}

$conn->close();
?>

<header class="border-bottom">
  <div class="container d-flex flex-wrap align-items-center justify-content-between py-2 mb-2">
    <a href="../index.html" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
      <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
        <circle cx="20" cy="20" r="20" fill="#007bff"/>
        <text x="20" y="25" font-size="20" text-anchor="middle" fill="#fff" font-family="Arial, sans-serif">I</text>
      </svg>
    </a>
    <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
      <li><a href="../index.html" class="nav-link px-2" id="homeLink">Home</a></li>
      <li><a href="../pages/explorar.html" class="nav-link px-2" id="explorarLink">Explorar</a></li>
      <li><a href="../pages/registrar_aloj.html" class="nav-link px-2" id="registrarLink">Registrar alojamiento</a></li>
    </ul>
    <div class="col-md-3 text-end d-flex justify-content-end">
      <?php if ($is_authenticated && $user): ?>
        <div class="dropdown">
          <button class="btn profile-button d-flex align-items-center p-1" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($profile_image); ?>" alt="Profile" class="rounded-circle" width="40" height="40">
            <span class="ms-2"><?php echo htmlspecialchars($user['name']); ?></span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
            <li><a class="dropdown-item" href="../pages/perfil.html">Mi perfil</a></li>
            <li><a class="dropdown-item" href="../logout.php">Cerrar sesión</a></li>
          </ul>
        </div>
      <?php else: ?>
        <div class="dropdown">
          <button class="btn profile-button d-flex align-items-center p-1" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://via.placeholder.com/40" alt="Profile" class="rounded-circle">
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Inicia sesión</a></li>
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#registerModal">Regístrate</a></li>
            <li><a class="dropdown-item" href="../pages/ayuda.html">Ayuda</a></li>
          </ul>
        </div>
      <?php endif; ?>
    </div>
  </div>
</header>

