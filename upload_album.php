<?php
  require 'controllers/MainController.php';
  $curDate = date('Y-m-d');

  $isAdmin = $_SESSION['isAdmin'] ?? false;
  if (!$isAdmin) {
    echo "
    <script>
      alert('Unauthorized access');
      window.location.href='/';
    </script>";
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" type="text/css" href="css/upload_album.css">
  <link rel="stylesheet" type="text/css" href="css/navbar.css">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php
      echo 'Binotify · Tambah Album';
    ?>
  </title>
</head>
<body>
  <div class="container">
    <?php
      include('templates/navbar.php');
    ?>
    <div class="form-container">
      <div class="form-header">
        <h1>Tambah Album</h1>
      </div>
      <form method="POST" id="form-upload" action="" class="form-wrapper">
        <div id="error-container">
        </div>
        <div class="input-container">
          <label>Judul</label>
          <input type="text" placeholder="Racing Into The Night" name="judul" />
        </div>
        <div class="input-container">
          <label>Penyanyi</label>
          <input type="text" placeholder="Yoasobi" name="penyanyi" />
        </div>
        <div class="input-container">
          <label>Genre</label>
          <input type="text" placeholder="Pop" name="genre" />
        </div>
        <div class="input-container">
          <label>Tanggal Terbit</label>
          <input type="date" name="tanggal_terbit" value=<?php echo $curDate ?> />
        </div>
        <div class="input-container">
          <label>Image</label>
          <input type="file" accept="image/*" id="imageToUpload" name="imageToUpload" />
        </div>
        <div class="button-container">
          <button class="form-button" type="submit" name="upload-album">Upload Album</button>
        </div>
      </form>
    </div>
  </div>
  <script>
    document.getElementById("form-upload").addEventListener("submit", function(e) {
      e.preventDefault();
      const formData = new FormData(document.getElementById("form-upload"));
      const xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
          const res = this.responseText.includes("<br>") ? 
            this.responseText.split("<br>")[1] : this.responseText;
          const response = JSON.parse(res);
          if (response.status === 200) {
            alert(response.message);
            document.getElementById("form-upload").reset();
            window.location.href = "/album_list.php";
          } else {
            document.getElementById("error-container").innerHTML = `
              <div class="error-message">
                <p>${response.message}</p>
              </div>
            `;
          }
        }
      };
      xhttp.open("POST", "api/upload_album.php", true);
      xhttp.send(formData);
    })
  </script>
</body>
</html>