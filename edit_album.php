<?php
  require('controllers/MainController.php');
  $isAdmin = $_SESSION['isAdmin'] ?? null;
  $album = new AlbumController($db);
  $albumData = $album->getSingleAlbum($_GET['album_id']);
  $albumSong = $album->getAlbumSong($_GET['album_id']);
  
  $judul = $albumData['judul'] ?? null;
  $penyanyi = $albumData['penyanyi'] ?? null;
  $total_duration = $albumData['total_duration'] ?? null;
  $image_path = $albumData['image_path'] ?? null;
  $tanggal_terbit = $albumData['tanggal_terbit'] ?? null;
  $genre = $albumData['genre'] ?? null;
  $listValidSong = $song->getValidSong($penyanyi);

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['update-album'])) {
      $album = new AlbumController($db);
      $albumData = $album->getSingleAlbum($_GET['album_id']);
      $target_dir_image = "./assets/images/album/";
      $imageFile = $_FILES['imageToUpload']['name'] ?? null;
      $target_file_image = $imageFile ? $target_dir_image . $_GET['album_id'] . '_' . date('Y-m-d_H-i-s') . '.' . pathinfo($_FILES["imageToUpload"]["name"], PATHINFO_EXTENSION) : $albumData['image_path'];

      $judulForm = $_POST['judul'];
      $genreForm = $_POST['genre'];
      $tanggal_terbit_form = $_POST['tanggal_terbit'];

      try {
        if ($imageFile) {
          unlink($image_path);
          move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file_image);
          $image_path = $_FILES["imageToUpload"]["name"] ? $target_file_image : $image_path;
        }
        $album->updateAlbum($_GET['album_id'], $judulForm, $genreForm, $tanggal_terbit_form, $image_path);
        redirect('album.php?album_id=' . $_GET['album_id']);
      } catch (Exception $e) {
        echo "<script>alert('{$e->getMessage()}')</script>";
      }
    }
  }

  function echoEditAlbum($judul, $penyanyi, $total_duration, $image_path, $tanggal_terbit, $genre, $albumSong, $listValidSong) {
    $isAdmin = $_SESSION['isAdmin'] ?? false;
    $qty = count($albumSong);
    $songList = '';
    $deleteSrc = './assets/images/component/trash.png';
    $plusSrc = './assets/images/component/add-edit.png';
    foreach ($albumSong as $song) {
      $jud = $song['judul'];
      $songList .= "
        <div class=\"song-container\" id={$song['song_id']}>
          <div class=\"song-title\">
            <div class=\"title\">
              <a>{$song['judul']}</a>
              <p class=\"song-artist\">{$song['penyanyi']}</p>
            </div>
          </div>
          <img src='{$deleteSrc}' height='30' class='song-remove' onClick='removeSong({$song['song_id']}, \"{$jud}\", \"{$song['penyanyi']}\")' /> 
        </div>
      ";
    }
    if (count($listValidSong) > 0) {
      $listSelect = "
        <div class='valid-song-wrapper' id='dropdown-wrapper'>
          <select name='songAlbum' id='songAlbum' class='song-select'>
            <option value=''>Pilih Lagu</option>
      ";
      foreach ($listValidSong as $song) {
        $listSelect .= "
          <option value={$song['song_id']} id={$song['song_id']}>{$song['judul']}</option>
        ";
      }

      $listSelect .= "
              </option>
            </select>
          <img src='{$plusSrc}' id='butPlus' height='30' class='song-add' onClick='addSong()' />
        </div>
        ";
    } else {
      $listSelect = NULL;
    }
    $html = <<<"EOT"
      <div class="edit-container" id='main-edit'>
        <div class="edit-header">
          <img src=$image_path height="200" alt="cover" id='image-content' />
          <div class="edit-header-info">
            <h5>ALBUM</h5>
            <h1 id='judul-content'>$judul</h1>
            <div class="edit-header-info-inline">
              <p class="penyanyi" id="singer">$penyanyi</p>
              <p id='date-content'> · $tanggal_terbit · </p>
              <p id='qty-content'>$qty</p>
            </div>
            <div class='btn-container'>
              <a href='/album.php?album_id={$_GET['album_id']}'>
                <button class='btn green'>  
                  Back
                </button>
              </a>
              <div>
                <button class='btn red' onClick='deleteAlbum({$_GET['album_id']})'>
                  Hapus Album
                </button>
              </div>
            </div>
          </div>
        </div>
        <form method="POST" id="form-upload" class="form-wrapper" enctype="multipart/form-data">
          <div id="error-container">
          </div>
          <div class="input-container">
            <label>Judul</label>
            <input id='judul-form' type="text" placeholder="Racing Into The Night" name="judul" value="{$judul}" />
          </div>
          <div class="input-container">
            <label>Genre</label>
            <input id='genre-form' type="text" placeholder="Pop" name="genre" value="{$genre}" />
          </div>
          <div class="input-container">
            <label>Tanggal Terbit</label>
            <input id='date-form' type="date" name="tanggal_terbit" value={$tanggal_terbit} />
          </div>
          <div class="input-container">
            <label>Image</label>
            <input type="file" accept="image/*" id="imageToUpload" name="imageToUpload" />
          </div>
          <div class="button-container">
            <button class="form-button" type="submit" name="update-album">Save</button>
          </div>
        </form>
        <div class="song-wrapper" id="song-wrapper">
          $songList
        </div>
        $listSelect
      </div>
    EOT;
    echo $html;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" type="text/css" href="css/navbar.css">
  <link rel="stylesheet" type="text/css" href="css/edit_album.css">
  <link rel="icon" href="assets/images/component/spotify.png">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php
      echo 'Edit ' . $judul . ' · ' . $penyanyi;
    ?>
  </title>
</head>
<body>
  <div class="container">
    <?php
      include('templates/navbar.php');
      echoEditAlbum($judul, $penyanyi, $total_duration, $image_path, $tanggal_terbit, $genre, $albumSong, $listValidSong);
    ?>
  </div>
  <script async defer>
    function deleteAlbum(album_id) {
      const formData = new FormData();
      formData.append('album_id', album_id);
      const xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          const res = this.responseText.includes("<br>") ? 
            this.responseText.split("<br>")[1] : this.responseText;
          const response = JSON.parse(res);
          if (response.status == 200) {
            alert(response.message);
            window.location.href = '/album_list.php';
          } else {
            alert(response.message);
          }
        }
      };
      xhttp.open("DELETE", `/api/delete_album.php?album_id=${album_id}`, true);
      xhttp.send(formData);
    }

    function addSong() {
      const albumId = <?php echo $_GET['album_id'] ?>;
      const songId = document.getElementById('songAlbum').value;
      const formData = new FormData();
      formData.append('song_id', songId);
      formData.append('album_id', <?php echo $_GET['album_id']; ?>);
      const xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          const res = this.responseText.includes("<br>") ? 
            this.responseText.split("<br>")[1] : this.responseText;
          const response = JSON.parse(res);
          if (response.status == 200) {
            document.getElementById('qty-content').innerHTML = parseInt(document.getElementById('qty-content').innerHTML) + 1;
            document.getElementById(songId).remove();
            var newDiv = document.createElement("div");
            newDiv.setAttribute('class', 'song-container');
            newDiv.setAttribute('id', songId);
            newDiv.innerHTML = `
              <div class='song-title'>
                <div class='title'>
                  <a>${response.data.judul}</a>
                  <p class='song-artist'>${response.data.penyanyi}</p>
                </div>
              </div>
              <img src='./assets/images/component/trash.png' height='30' class='song-remove' onClick='removeSong(${songId}, \"${response.data.judul}\", \"${response.data.penyanyi}\")' />
            `;
            document.getElementById('song-wrapper').appendChild(newDiv);
            if (document.getElementById('songAlbum').length == 1) {
              document.getElementById('dropdown-wrapper').remove();
            }
          }
          if (response.message !== 'Song ID tidak boleh kosong') {
            alert(response.message);
          }
        }
      };
      xhttp.open("POST", `/api/add_song_album.php?song_id=${songId}&album_id=${albumId}`, true);
      xhttp.send(formData);
    }

    function removeSong(song_id, judul, penyanyi) {
      const albumId = <?php echo $_GET['album_id']; ?>;
      const formData = new FormData();
      formData.append('song_id', song_id);
      const xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          const res = this.responseText.includes("<br>") ? 
            this.responseText.split("<br>")[1] : this.responseText;
          const response = JSON.parse(res);
          if (response.status == 200) {
            document.getElementById(song_id).remove();
            document.getElementById('qty-content').innerHTML = parseInt(document.getElementById('qty-content').innerHTML) - 1;
            const singer = document.getElementById('singer').textContent;
            if (singer.toLowerCase() === penyanyi.toLowerCase()) {
              var newDiv = document.createElement('option');
              newDiv.setAttribute('value', song_id);
              newDiv.setAttribute('id', song_id);
              newDiv.innerHTML = judul;
              if (!document.getElementById('songAlbum')) {
                var selDiv = document.createElement('select');
                selDiv.setAttribute('id', 'songAlbum');
                selDiv.setAttribute('name', 'songAlbum');
                selDiv.setAttribute('class', 'song-select');
                var defDiv = document.createElement('option');
                defDiv.setAttribute('value', '');
                defDiv.innerHTML = 'Pilih Lagu';
                selDiv.appendChild(defDiv);
                selDiv.appendChild(newDiv);
                var butDiv = document.createElement('img');
                butDiv.setAttribute('id', 'butPlus');
                butDiv.setAttribute('src', './assets/images/component/add-edit.png');
                butDiv.setAttribute('height', '30');
                butDiv.setAttribute('class', 'song-add');
                butDiv.setAttribute('onClick', 'addSong()');
                var dropDiv = document.createElement('div');
                dropDiv.setAttribute('class', 'valid-song-wrapper');
                dropDiv.setAttribute('id', 'dropdown-wrapper');
                dropDiv.appendChild(selDiv);
                dropDiv.appendChild(butDiv);
                document.getElementById('main-edit').appendChild(dropDiv);
              } else {
                document.getElementById('songAlbum').appendChild(newDiv);
              }
            }
          }
          alert(response.message);
        }
      };
      xhttp.open("UPDATE", `/api/remove_song_album.php?song_id=${song_id}&album_id=${albumId}`, true);
      xhttp.send(formData);
    }
  </script>
</body>
</html>