<?php
session_start();
include_once('db.php');

// Check login
if (!isset($_SESSION['userId'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['userId'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

// Fetch user details (fresh)
$stmt = $con->prepare("SELECT userName, email FROM tbl_user WHERE userId = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <link rel="stylesheet" href="style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS (Optional for Modal or other features) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>

<?php include('navbar.php'); ?>
<div class="profile">
    <div class="container">
        <div class="custom-form">
            <h2 class="mb-3 text-center">Welcome, <?= htmlspecialchars($user['userName']); ?></h2>
            <p class="text-center"><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>

            <?php if (!empty($success)) : ?>
                <div class="alert alert-success"><?= $success; ?></div>
            <?php elseif (!empty($error)) : ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <hr>

            <h5>Edit Email & Password</h5>
            <form method="POST">
                <div class="mb-3">
                    <label for="new_email" class="form-label">New Email</label>
                    <input type="email" id="new_email" name="new_email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                </div>

                <h6 class="mt-4">Confirm with Current Credentials</h6>

                <div class="mb-3">
                    <label for="current_email" class="form-label">Current Email</label>
                    <input type="email" id="current_email" name="current_email" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Update Profile</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
