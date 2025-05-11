<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ImageUploader - Home</title>
  <link rel="stylesheet" href="style.css">


  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome (for icons) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

</head>
<body>
    <?php include('navbar.php'); ?>

    <!-- Hero Section -->
    <div class="hero">
    <div class="container">
        <h1 class="display-4 fw-bold">Welcome to ImageUploader</h1>
        <p class="lead">Upload, manage and view your images easily and securely.</p>
        <a href="main.php" class="btn btn-light btn-lg mt-3">Upload Now</a>
    </div>
    </div>

    <!-- Features Section -->
    <div class="container my-5">
    <div class="row text-center">
        <div class="col-md-4">
        <div class="feature-icon">
            <i class="fas fa-cloud-upload-alt"></i>
        </div>
        <h5>Fast Uploads</h5>
        <p>Upload images quickly and store them securely on our server.</p>
        </div>
        <div class="col-md-4">
        <div class="feature-icon">
            <i class="fas fa-images"></i>
        </div>
        <h5>Manage Media</h5>
        <p>Organize your images, view upload dates, and manage your gallery.</p>
        </div>
        <div class="col-md-4">
        <div class="feature-icon">
            <i class="fas fa-lock"></i>
        </div>
        <h5>Secure Storage</h5>
        <p>Your files are stored safely with modern security practices in place.</p>
        </div>
    </div>
    </div>

    <!-- Footer -->
    <footer>
    <div class="container">
        <p>&copy; 2025 ImageUploader. All Rights Reserved.</p>
    </div>
    </footer>

    <!-- Bootstrap JS and Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
