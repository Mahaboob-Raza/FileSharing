<?php
include_once('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $targetDir = "images/"; // Make sure this folder exists and is writable
    //$targetFile = $targetDir . basename($_FILES["file"]["name"]);
    $original_file = $targetDir . mt_rand(0000,9999).'-'.basename($_FILES["file"]["name"]);
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $original_file)) {
        
        $insertMedia = $con->prepare("INSERT INTO `tbl_media` (`userId`, `path`, `media_name`) VALUES (?, ?, ?)");
        $insertMedia->execute(['1',$original_file,$original_file]);
        if($insertMedia->rowCount() > 0){
            echo "File uploaded successfully!";
        }else{
            echo "Db error";
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
