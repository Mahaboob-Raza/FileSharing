<?php
session_start();
include_once('db.php');

// Check login
if (!isset($_SESSION['userId'])) {
    header("Location: signin.php");
    exit();
}

$message = '';
$success = '';
$error = '';

if(isset($_POST['delete'])) {
    $imageToDelete = $_POST['delete_image'];
    if(file_exists($imageToDelete)) {
        if(unlink($imageToDelete)) {
            $message = "<p class='profile-msg-success'>Image deleted successfully!</p>";
        } else {
            $message = "<p class='profile-msg-error'>Failed to delete image!</p>";
        }
    } else {
        $message = "<p class='profile-msg-error'>File not found!</p>";
    }
}

$user_id = $_SESSION['userId'];

// Handle form submission for profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_email'])) {
    $new_email      = $_POST['new_email'];
    $new_password   = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $current_email  = $_POST['current_email'];
    $current_pass   = $_POST['current_password'];

    // Fetch current user info
    $stmt = $con->prepare("SELECT email, password FROM tbl_user WHERE userId = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Validate current credentials
    if ($user && $user['email'] == $current_email && password_verify($current_pass, $user['password'])) {
        // Update email & password
        $update = $con->prepare("UPDATE tbl_user SET email = ?, password = ? WHERE userId = ?");
        if ($update->execute([$new_email, $new_password, $user_id])) {
            $success = "Profile updated successfully.";
        } else {
            $error = "Failed to update profile.";
        }
    } else {
        $error = "Current credentials incorrect.";
    }
}

// Fetch user details
$stmt = $con->prepare("SELECT userName, email FROM tbl_user WHERE userId = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Your Profile</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="profile-container container my-5">
    <h2 class="mb-4 text-center profile-welcome">Welcome, <?= htmlspecialchars($user['userName']); ?>!</h2>

    <?php if ($message): ?>
        <div class="mb-3"><?= $message ?></div>
    <?php endif; ?>

    <h3 class="mb-3">Your Uploaded Images:</h3>
    <?php
    $folder = "images/";
    $images = glob($folder."*");
    if(count($images) > 0){
        echo "<div class='row profile-image-row'>";
        foreach($images as $image) {
            echo "<div class='col-md-4 col-sm-6 mb-4'>";
            echo "  <div class='profile-image-card card shadow-sm h-100'>";
            echo "      <a href='$image' target='_blank'>";
            echo "          <img src='$image' class='card-img-top rounded img-fluid' alt='User Image'>";
            echo "      </a>";
            echo "      <div class='card-body text-center'>";
            echo "          <div class='d-flex justify-content-center gap-2'>";
            echo "              <a href='$image' download class='btn btn-success btn-sm'>Download</a>";
            echo "              <form method='post' class='d-inline'>";
            echo "                  <input type='hidden' name='delete_image' value='$image'>";
            echo "                  <button type='submit' name='delete' class='btn btn-danger btn-sm' onclick=\"return confirm('Delete this image?')\">Delete</button>";
            echo "              </form>";
            echo "          </div>";
            echo "      </div>";
            echo "  </div>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p class='text-muted'>No images uploaded yet.</p>";
    }
    ?>

    <div class="profile-edit-form mt-5 mx-auto" style="max-width: 450px;">
        <h4 class="mb-3 text-center text-primary">Edit Email & Password</h4>

        <?php if (!empty($success)) : ?>
            <div class="alert alert-success"><?= $success; ?></div>
        <?php elseif (!empty($error)) : ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="new_email" class="form-label">New Email</label>
                <input type="email" id="new_email" name="new_email" class="form-control profile-input" required />
                <div class="invalid-feedback">Please enter a valid email.</div>
            </div>

            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" id="new_password" name="new_password" class="form-control profile-input" required />
                <div class="invalid-feedback">Please enter a new password.</div>
            </div>

            <hr>

            <h6>Confirm with Current Credentials</h6>

            <div class="mb-3">
                <label for="current_email" class="form-label">Current Email</label>
                <input type="email" id="current_email" name="current_email" class="form-control profile-input" required />
                <div class="invalid-feedback">Please enter your current email.</div>
            </div>

            <div class="mb-4">
                <label for="current_password" class="form-label">Current Password</label>
                <input type="password" id="current_password" name="current_password" class="form-control profile-input" required />
                <div class="invalid-feedback">Please enter your current password.</div>
            </div>

            <button type="submit" class="btn btn-primary w-100 profile-btn-update">Update Profile</button>
        </form>
    </div>
</div>


<script>
// Bootstrap validation
(() => {
  'use strict';
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if(!form.checkValidity()){
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
})();
</script>

</body>
</html>
