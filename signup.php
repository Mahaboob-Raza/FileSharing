<?php
include_once('db.php');

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $email    = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  // Check for duplicate email
  $check = $con->prepare("SELECT * FROM tbl_user WHERE email = ?");
  $check->execute([$email]);

  if ($check->rowCount() > 0) {
    $error = "Email already registered.";
  } else {
    $stmt = $con->prepare("INSERT INTO tbl_user (userName, email, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $email, $password])) {
      $success = "Account created successfully. You can now log in.";
    } else {
      $error = "Something went wrong. Try again.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up - ImageUploader</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<?php include('navbar.html'); ?>
<body class="auth">
<div >
    <div class="auth-container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <h2 class="text-center mb-4">Create an Account</h2>

                <?php 
                    if (!empty($success)) echo "<div class='alert alert-success'>$success</div>";
                    if (!empty($error))   echo "<div class='alert alert-danger'>$error</div>";
                ?>

                <form method="POST" action="">
                    <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                    <p class="text-center mt-3">Already have an account? <a href="signin.php">Login</a></p>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
