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
  $listValidSong = $song->getValidSong('tulus');

  function convertSecToFullTime($duration) {
    $temp = gmdate("H:i:s", $duration);
    $temp = explode(':', $temp);
    $dur = '';
    if ($temp[0] != '00') {
      $dur .= ltrim($temp[0], '0') . ' jam ';
    }
    if ($temp[1] != '00') {
      $dur .= ltrim($temp[1], '0') . ' menit ';
    }
    if ($temp[2] != '00') {
      $dur .= ltrim($temp[2], '0'). ' detik';
    }
    return $dur;
  }

  function echoEditAlbum($judul, $penyanyi, $total_duration, $image_path, $tanggal_terbit, $genre, $albumSong, $listValidSong) {
    $isAdmin = $_SESSION['isAdmin'] ?? false;
    $total_time = convertSecToFullTime($total_duration);
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
      ";
    } else {
      $listSelect = NULL;
    }
    $html = <<<"EOT"
      <div class="edit-container">
        <div class="edit-header">
          <img src=$image_path height="200" alt="cover" />
          <div class="edit-header-info">
            <h5>ALBUM</h5>
            <h1>$judul</h1>
            <div class="edit-header-info-inline">
              <p class="penyanyi" id="singer">$penyanyi</p>
              <p> · $tanggal_terbit · </p>
              <p>$qty,
                <p class="duration">
                  $total_time
                </p>
              </p>
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
        <form method="POST" id="form-upload" action="" class="form-wrapper">
          <div id="error-container">
          </div>
          <div class="input-container">
            <label>Judul</label>
            <input type="text" placeholder="Racing Into The Night" name="judul" value="{$judul}" />
          </div>
          <div class="input-container">
            <label>Genre</label>
            <input type="text" placeholder="Pop" name="genre" value="{$genre}" />
          </div>
          <div class="input-container">
            <label>Tanggal Terbit</label>
            <input type="date" name="tanggal_terbit" value={$tanggal_terbit} />
          </div>
          <div class="input-container">
            <label>Image</label>
            <input type="file" accept="image/*" id="imageToUpload" name="imageToUpload" />
          </div>
          <div class="button-container">
            <button class="form-button" type="submit" name="upload-album">Save</button>
          </div>
        </form>
        <div class="song-wrapper" id="song-wrapper">
          $songList
        </div>
        <div class="valid-song-wrapper" id="dropdown-wrapper">
          $listSelect
        </div>
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
  <script>
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
              document.getElementById('songAlbum').remove();
              document.getElementById('butPlus').remove();
            }
          }
          if (response.message !== 'Song ID tidak boleh kosong') {
            alert(response.message);
          }
        }
      };
      xhttp.open("POST", `/api/add_song_album.php?song_id=${songId}`, true);
      xhttp.send(formData);
    }

    function removeSong(song_id, judul, penyanyi) {
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
            const singer = document.getElementById('singer').value;
            if (singer.toLowerCase() === penyanyi.toLowerCase()) {
              var newDiv = document.createElement('option');
              newDiv.setAttribute('value', song_id);
              newDiv.setAttribute('id', song_id);
              newDiv.innerHTML = judul;
              if (document.getElementById('songAlbum') !== null) {
                document.getElementById('songAlbum').appendChild(newDiv);
              } else {
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
                document.getElementById('dropdown-wrapper').appendChild(selDiv);
                document.getElementById('dropdown-wrapper').appendChild(butDiv);
              }
            }
          }
          alert(response.message);
        }
      };
      xhttp.open("UPDATE", `/api/remove_song_album.php?song_id=${song_id}`, true);
      xhttp.send(formData);
    }
  </script>
</body>
</html>