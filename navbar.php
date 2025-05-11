<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (isset($_GET['logout'])) {
  session_unset();
  session_destroy();
  header("Location: signin.php");
  exit();
}
?>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <!-- Logo or Brand Name -->
      <a class="navbar-brand" href="index.php">File Sharing</a>

      <!-- Hamburger Button (for mobile view) -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar Links -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About Us</a>
          </li>
          <?php if (isset($_SESSION['userId'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="?logout=true">Log Out</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="signin.php">Log In</a>
            </li>
          <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</body>