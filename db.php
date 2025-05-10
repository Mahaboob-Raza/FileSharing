<?php
$islocal = true;

if($islocal){
    $servername = "localhost";  // Your server's address (or 'localhost' if on the same server)
    $username = "root";         // Your database username
    $password = "";             // Your database password
    $dbname = "ImageUploader";  // Your database name
}else{
    $servername = "localhost";  // Your server's address (or 'localhost' if on the same server)
    $username = "stillkon_imguploadUser";         // Your database username
    $password = "apple@123...";             // Your database password
    $dbname = "stillkon_imageuploader";  // Your database name
}

$con = null;
try {
    // Create PDO connection
    $con = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set PDO error mode to exception
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>