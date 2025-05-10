<?php 
include_once('db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>File Upload with jQuery and Bootstrap</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Bootstrap JS (Optional for Modal or other features) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    .container {
      margin-top: 50px;
    }
  </style>
</head>
<body>
  <?php include('navbar.html'); ?>
  <div class="container">
    <h2 class="text-center">Upload a File</h2>
    <div class="row justify-content-center">
      <div class="col-md-6">
        <form id="uploadForm" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="fileInput" class="form-label">Choose a file</label>
            <input type="file" class="form-control" id="fileInput" name="file" required>
          </div>
          <button type="submit" class="btn btn-primary">Upload</button>
        </form>

        <div id="response" class="mt-3"></div>
        <div class="row">
          <div class="col-md-12">
            <?php
              $getMediaByUser = $con->prepare("Select * from tbl_media where userId = '1' order by mediaId desc");
              $getMediaByUser->execute();
              if($getMediaByUser->rowCount() > 0){
                $allMedia = $getMediaByUser->fetchAll();
                foreach($allMedia as $media){
                  // print_r($media['path']);
                  $path = $media['path'];
                  
                  echo "<img src='$path' width='100%' />";
                }
              }
            ?>
          </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#uploadForm').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
          url: 'upload.php', // Your server-side file handling script
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function(response) {
            console.log(response)
            $('#response').html('<div class="alert alert-success">File uploaded successfully!</div>');
            setTimeout(() => {
              window.location.reload()
            }, 2000);
          },
          error: function() {
            $('#response').html('<div class="alert alert-danger">Error uploading file. Please try again.</div>');
          }
        });
      });
    });
  </script>
</body>
</html>
