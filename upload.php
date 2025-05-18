<?php
session_start();
include_once('db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    // Check login
    if (!isset($_SESSION['userId'])) {
        echo json_encode(['status' => 'error', 'message' => 'You must be logged in to upload files.']);
        exit();
    }

    $userId = $_SESSION['userId'];

    // Allowed extensions
    $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
    $allowedDocExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];

    $file = $_FILES['file'];
    $fileName = basename($file["name"]);
    $fileTmpName = $file["tmp_name"];
    $fileSize = $file["size"];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Size limit: 10MB (adjust as needed)
    $maxFileSize = 10 * 1024 * 1024;
    if ($fileSize > $maxFileSize) {
        echo json_encode(['status' => 'error', 'message' => 'File size exceeds 10MB limit.']);
        exit();
    }

    // Determine folder
    if (in_array($fileExt, $allowedImageExtensions)) {
        $targetDir = "images/";
    } elseif (in_array($fileExt, $allowedDocExtensions)) {
        $targetDir = "documents/";
    } else {
        echo json_encode(['status' => 'error', 'message' => 'File type not allowed.']);
        exit();
    }

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Sanitize original filename (remove special chars)
    $safeFileName = preg_replace("/[^a-zA-Z0-9-_\.]/", "_", $fileName);

    // Generate unique filename
    $newFileName = uniqid() . '-' . mt_rand(1000,9999) . '-' . $safeFileName;
    $targetFilePath = $targetDir . $newFileName;

    // Move file
    if (move_uploaded_file($fileTmpName, $targetFilePath)) {
        chmod($targetFilePath, 0644); // Set file permission

        // Insert to DB
        $insertMedia = $con->prepare("INSERT INTO tbl_media (userId, path, media_name) VALUES (?, ?, ?)");
        $insertMedia->execute([$userId, $targetFilePath, $fileName]);

        if ($insertMedia->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'File uploaded successfully!']);
        } else {
            // Delete file if DB insert fails
            unlink($targetFilePath);
            echo json_encode(['status' => 'error', 'message' => 'Database error.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'There was an error uploading your file.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No file uploaded or invalid request.']);
}
