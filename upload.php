<?php
session_start();  // Start the session first
include_once('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    // Check if user is logged in
    if (!isset($_SESSION['userId'])) {
        echo "You must be logged in to upload files.";
        exit();
    }

    $userId = $_SESSION['userId'];  // Get logged-in user's ID

    $targetDir = "images/"; // Make sure this folder exists and is writable
    $original_file = $targetDir . mt_rand(0000,9999).'-'.basename($_FILES["file"]["name"]);

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $original_file)) {
        $insertMedia = $con->prepare("INSERT INTO `tbl_media` (`userId`, `path`, `media_name`) VALUES (?, ?, ?)");
        $insertMedia->execute([$userId, $original_file, $original_file]);

        if($insertMedia->rowCount() > 0){
            echo "File uploaded successfully!";
        } else {
            echo "Db error";
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
