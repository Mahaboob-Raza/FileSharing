<?php 
session_start(); 
include_once('db.php');

// Redirect if not logged in
if (!isset($_SESSION['userId'])) {
  header("Location: signin.php");
  exit();
}
$currentUserId = $_SESSION['userId'];

// Delete handler
if (isset($_POST['delete'])) {
  $mediaId = $_POST['mediaId'];
  $filePath = $_POST['filePath'];

  // Delete file from server
  if (file_exists($filePath)) {
    unlink($filePath);
  }

  // Delete from database
  $delete = $con->prepare("DELETE FROM tbl_media WHERE mediaId=? AND userId=?");
  $delete->execute([$mediaId, $currentUserId]);
  
  $deleteMsg = "<div class='alert alert-success'>File deleted successfully!</div>";
}

// Helper function to get file extension
function getFileExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Drive</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    iframe {
      width: 100%;
      border: none;
    }
  </style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="container-fluid mt-4">
  <?php if(!empty($deleteMsg)) echo $deleteMsg; ?>

  <div class="row">

    <!-- Left Sidebar -->
    <div class="col-md-3 bg-light p-4 rounded shadow-sm drive-left-panel">
      <h5>Your Drive</h5>
      <form id="uploadForm" enctype="multipart/form-data" class="mt-3">
        <label for="fileInput" class="form-label">
          <div class="btn btn-primary w-100 p-3" style="cursor:pointer;">
            <span style="font-size: 24px;">+</span> Upload
          </div>
        </label>
        <input type="file" id="fileInput" name="file" class="form-control d-none" required>
      </form>
      <div id="uploadResponse" class="mt-2"></div>
    </div>

    <!-- Right Content -->
    <div class="col-md-9 right-content">
      <div class="row">

        <!-- Images Section -->
        <div class="col-md-12 mt-2 mb-4">
          <h5>Images</h5>
          <div class="row row-cols-1 row-cols-md-4 g-3">
            <?php
              $getImages = $con->prepare("SELECT * FROM tbl_media WHERE userId=? ORDER BY mediaId DESC");
              $getImages->execute([$currentUserId]);

              $imageExtensions = ['jpg','jpeg','png','gif','bmp','webp'];

              $imageCount = 0;

              foreach($getImages->fetchAll() as $media){
                $ext = getFileExtension($media['path']);
                if(in_array($ext, $imageExtensions)){
                  $imageCount++;
                  echo "
                    <div class='col'>
                      <div class='card h-100 shadow-sm'>
                        <a href='{$media['path']}' target='_blank'>
                          <img src='{$media['path']}' class='card-img-top' alt='Image' style='cursor:pointer;'>
                        </a>
                        <div class='card-body text-center'>
                          <a href='{$media['path']}' download class='btn btn-sm btn-primary me-2'>Download</a>
                          <form method='POST' class='d-inline'>
                            <input type='hidden' name='mediaId' value='{$media['mediaId']}'>
                            <input type='hidden' name='filePath' value='{$media['path']}'>
                            <button type='submit' name='delete' class='btn btn-sm btn-danger' onclick=\"return confirm('Delete this file?')\">Delete</button>
                          </form>
                        </div>
                      </div>
                    </div>";
                }
              }
              if($imageCount == 0){
                echo "<p class='text-muted'>No images uploaded yet.</p>";
              }
            ?>
          </div>
        </div>

        <!-- Documents Section -->
        <div class="col-md-12 mb-4">
          <h5>Documents</h5>
          <div class="row row-cols-1 row-cols-md-3 g-3">
            <?php
              $getDocs = $con->prepare("SELECT * FROM tbl_media WHERE userId=? ORDER BY mediaId DESC");
              $getDocs->execute([$currentUserId]);

              $docExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];

              $docCount = 0;

              foreach($getDocs->fetchAll() as $media){
                $ext = getFileExtension($media['path']);
                if(in_array($ext, $docExtensions)){
                  $docCount++;

                  // For PDF show iframe, else show an icon or text fallback
                  if($ext === 'pdf'){
                    $docPreview =" <a href='{$media['path']}' target='_blank' style='display:block;'>
                                      <iframe src='{$media['path']}' height='200' class='card-img-top' style='pointer-events:none; cursor:pointer;'></iframe>
                                    </a>";
                  } else {
                    // fallback for other docs: show an icon or text
                    $docPreview = "<div class='text-center p-5' style='font-size: 48px; color: #6c757d;'>
                                    <i class='bi bi-file-earmark-text'></i>
                                    <p>{$media['media_name']}</p>
                                   </div>";
                  }

                  echo "
                    <div class='col'>
                      <div class='card h-100 shadow-sm'>
                        $docPreview
                        <div class='card-body text-center'>
                          <a href='{$media['path']}' download class='btn btn-sm btn-primary me-2'>Download</a>
                          <form method='POST' class='d-inline'>
                            <input type='hidden' name='mediaId' value='{$media['mediaId']}'>
                            <input type='hidden' name='filePath' value='{$media['path']}'>
                            <button type='submit' name='delete' class='btn btn-sm btn-danger' onclick=\"return confirm('Delete this file?')\">Delete</button>
                          </form>
                        </div>
                      </div>
                    </div>";
                }
              }
              if($docCount == 0){
                echo "<p class='text-muted'>No documents uploaded yet.</p>";
              }
            ?>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#fileInput').change(function() {
      var formData = new FormData($('#uploadForm')[0]);
      $.ajax({
        url: 'upload.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
          $('#uploadResponse').html('<div class="alert alert-success">' + response + '</div>');
          setTimeout(() => { location.reload(); }, 1200);
        },
        error: function() {
          $('#uploadResponse').html('<div class="alert alert-danger">Upload failed. Try again.</div>');
        }
      });
    });
  });
</script>

<!-- Bootstrap Icons CDN for file icon -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</body>
</html>
