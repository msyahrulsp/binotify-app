<?php
require('controllers/MainController.php');
$songData = $song->getSingleSong($_GET['song_id']);

// FOR AUTENTIKASI ADMIN
session_start();
$isAdmin = $_SESSION['isAdmin'] ?? false;
// $isAdmin = true;

$isAuthenticated = !empty($_SESSION['user_id']); // 1 when session is defined
// $isAuthenticated = 1;
// echo 'isAdmin'.$isAdmin."<br>";
// echo 'isAuthenticated'.$isAuthenticated;

$judul = $songData['judul'] ?? null;
$penyanyi = $songData['penyanyi'] ?? null;
$tanggal = $songData['tanggal_terbit'] ?? null;
$genre = $songData['genre'] ?? null;
$durasi = $songData['duration'] ?? null;
$imagePath = $songData['image_path'] ?? null;
$songPath = $songData['audio_path'] ?? null;
$albumID = $songData['album_id'] ?? null;
$tombol_album = 'ini tombol album';

function echoSongDetail($judul, $penyanyi, $tanggal, $genre, $durasi, $imagePath, $songPath)
{
  $durasiMenit = floor(($durasi / 60) % 60);
  $durasiDetik = $durasi % 60;
  $time = strtotime($tanggal);
  $newDate = date('d-m-Y',$time);
  $html = <<<"EOT"
  <div class="divider"> 
        <div class="image">
          <img src="{$imagePath}" height="200" width="200" />
        </div>
        <div class="info">
          <p class="title">$judul</p>
          <div class="rest-info">
            <p>$penyanyi</p>
            <p>$durasiMenit menit $durasiDetik detik</p>
            <p>$genre</p>
            <p>$newDate</p>
          </div>
          <div id="audio-player-wrapper">
            <audio id="audio-player" controls source src="{$songPath}" type="audio/ogg">
            </audio>
            <p id="count-limit"></p>
          </div>
        </div>
      </div>
  EOT;
  echo $html;
}

function echoSongEdit($judul, $penyanyi, $tanggal, $genre, $durasi, $imagePath, $songPath)
{
  $html = <<<"EOT"
  <div class="divider"> 
        <div class="image">
          <img src="{$imagePath}" height="200" width="200" />
        </div>
        <div class="info">
        <p>Judul</p><input type="text" name="judul" value="$judul">
          <div class="rest-info">
          <p>Durasi $durasi</p>
          <input hidden type="text" name="durasi" value="$durasi">
          <p>Penyanyi $penyanyi</p>
          <input hidden type="text" name="penyanyi" value="$penyanyi">
            <p>Genre</p><input type="text" name="genre" value="$genre">
            
            <p>Tanggal</p><input type="date" name="tanggal" value="$tanggal">
          </div>
          <p>File lagu:</p>
    <input type="file" name="songToUpload" id="songToUpload"><br>
    <p>File gambar:</p>
    <input type="file" name="imageToUpload" id="imageToUpload"><br>
    <p>Album:</p>
    <span>TBD s input selection for available album</span><br>
        </div>
      </div>
  EOT;
  echo $html;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['save-edit-song'])) {
    // edit song logic
    $target_dir_song = "./assets/musics/";
    $target_dir_image = "./assets/images/";
    $songFile = $_FILES["songToUpload"]['name'] ?? null;
    $imageFile = $_FILES["imageToUpload"]['name'] ?? null;

    // change to existing path if song/image is not changed
    $target_file_song = $songFile ? $target_dir_song . 'song' . $_GET['song_id'] . '_' . date('Y-m-d_H-i-s') . '.' . pathinfo($_FILES["songToUpload"]["name"], PATHINFO_EXTENSION) : $songData['audio_path'];
    $target_file_image = $imageFile ? $target_dir_image . 'image' . $_GET['song_id'] . '_' . date('Y-m-d_H-i-s') . '.' . pathinfo($_FILES["imageToUpload"]["name"], PATHINFO_EXTENSION) : $songData['image_path'];

    // payload
    $judul = $_POST['judul'];
    $penyanyi = $_POST['penyanyi'];
    $tanggal = $_POST['tanggal'];
    $genre = $_POST['genre'];
    $durasi = $_POST['durasi'];
    $penyanyi = $_POST['penyanyi'];

    try {
      if ($songFile && $imageFile) {
        unlink($imagePath);
        unlink($songPath);
        move_uploaded_file($_FILES["songToUpload"]["tmp_name"], $target_file_song);
        move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file_image);

        $songPath = $_FILES["songToUpload"]["name"] ? $target_file_song : $songPath;
        $imagePath = $_FILES["imageToUpload"]['name'] ? $target_file_image : $imagePath;
      } elseif ($songFile) {
        unlink($songPath);
        move_uploaded_file($_FILES["songToUpload"]["tmp_name"], $target_file_song);

        $songPath = $_FILES["songToUpload"]['name'] ? $target_file_song : $songPath;
      } elseif ($imageFile) {
        unlink($imagePath);
        move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file_image);

        $imagePath = $_FILES["imageToUpload"]['name'] ? $target_file_image : $imagePath;
      }

      $song->updateSong($judul, $penyanyi, $tanggal, $genre, $durasi, $target_file_song, $target_file_image, $_GET['song_id'], $albumID);
      echo "<script type='text/javascript'>alert('Lagu berhasil diubah.');</script>";
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  } elseif (isset($_POST['delete-song'])) {
    $songData = $song->deleteSong($_GET['song_id'], $durasi, $albumID);
    redirect('/');
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" type="text/css" href="css/navbar.css">
  <link rel="stylesheet" href="css/song.css">
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>
    <?php
    echo $songData['judul'] . ' · ' . $songData['penyanyi'];
    ?>
  </title>
</head>

<body>
  <div class="container">
    <?php
    include('templates/navbar.php'); 
    ?>
    <div class="container-song">
      <form method="post" action="<?php echo 'song.php?song_id=' . $_GET['song_id'] ?>" enctype="multipart/form-data">
        <?php if (isset($_POST['edit-song'])) {
          echoSongEdit($judul, $penyanyi, $tanggal, $genre, $durasi, $imagePath, $songPath);
        } elseif (isset($_POST['save-edit-song'])) {
          echoSongEdit($judul, $penyanyi, $tanggal, $genre, $durasi, $imagePath, $songPath);
        } else {
          echoSongDetail($judul, $penyanyi, $tanggal, $genre, $durasi, $imagePath, $songPath);
        }
        ?>
        <?php if ($isAdmin && (isset($_POST['edit-song']) or isset($_POST['save-edit-song']))) {
          echo '<button class="save-btn" type="submit" name="save-edit-song">Save</button>';
          echo "<a class=\"cancel-btn\" href='./song.php?song_id={$_GET['song_id']}'>Cancel</a>";
        } elseif ($isAdmin && !isset($_POST['edit-song'])) {
          echo '<button class="edit-btn" type="submit" name="edit-song">Edit</button>';
          echo '<button class="delete-btn" type="submit" name="delete-song">Delete</button>';
        }
        ?>
      </form>
    </div>
  </div>

</body>
<script>
  function getCookie(cname) {
    // get cookie based on key
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }

  function setCookie(cname, cvalue, second) {
    // set cookie based on name, value, and expire time
    const d = new Date();
    d.setTime(d.getTime() + (second * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    console.log('cookie set', cname + "=" + cvalue + ";" + expires + ";path=/")
  }

  function setCookieEndDay(cname, cvalue, second) {
    // set cookie based on name, value, and expire time at the end of the day
    const date = new Date();
    const midnight = new Date(date.getFullYear(), date.getMonth(), date.getDate(), 23, 59, 59);
    let expires = "expires=" + midnight.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    console.log('cookie set', cname + "=" + cvalue + ";" + expires + ";path=/")
  }

  let playedSong = [];
  const isAuthenticated = '<?php echo $isAuthenticated; ?>';
  // empty string if is not authenticated, '1' if authenticated
  window.addEventListener("load", function() {
    console.log('session', isAuthenticated === '')
    if (!getCookie('playedSong')) {
      setCookieEndDay('playedSong', JSON.stringify(playedSong), 60);
    } else {
      const arrPlayedSong = JSON.parse(getCookie('playedSong'))
      console.log('current played', arrPlayedSong);
      if (isAuthenticated !== '1') {
        if (arrPlayedSong.length > 3) {
          document.getElementById("audio-player").style.pointerEvents = 'none';
          document.getElementById('count-limit').innerHTML = 'Kamu telah mendengarkan lagu sebanyak 3 kali. Coba lagi di hari selanjutnya.';
        }
      }
    }
  })
  // TODO: prevent from running if editing
  document.getElementById("audio-player").addEventListener("play", function() {
    const currentSong = document.getElementById("audio-player").src;
    let cookie = JSON.parse(getCookie('playedSong'));
    if (isAuthenticated !== '1') {
      if (cookie.length > 3) {
        document.getElementById("audio-player").pause();
        document.getElementById("audio-player").style.pointerEvents = 'none';
        document.getElementById('count-limit').innerHTML = 'Kamu telah mendengarkan lagu sebanyak 3 kali. Coba lagi di hari selanjutnya.';
      }
    }
    if (!cookie.includes(currentSong)) {
      cookie.push(currentSong);
      setCookieEndDay('playedSong', JSON.stringify(cookie), 60);
    }
  })
</script>

</html>