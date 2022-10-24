<?php
require('controllers/MainController.php');
echo '<br>' . $_GET['song_id'] . '<br>';
$songData = $song->getSingleSong($_GET['song_id']);
print_r($songData);
echo '<br>' . $songData['audio_path'];
echo '<br>' . '-------------debug------------' . '<br>';

$isAdmin = true;

$judul = $songData['judul'] ?? null;
$penyanyi = $songData['penyanyi'] ?? null;
$tanggal = $songData['tanggal_terbit'] ?? null;
$genre = $songData['genre'] ?? null;
$durasi = 0;
$imagePath = $songData['image_path'] ?? null;
$songPath = $songData['audio_path'] ?? null;
$tombol_album = 'ini tombol album';

function echoSongDetail($judul = 'ini judul', $penyanyi = 'ini penyanyi', $tanggal = 'ini tanggal', $genre = 'ini genre', $durasi = 'ini durasi', $imagePath = 'assets/images/defaultImage.jpg', $songPath = '', $tombol_album = 'ini tombol album')
{
  $html = <<<"EOT"
  <div class="divider"> 
        <div class="image">
          <img src="{$imagePath}" height="200" width="200" />
        </div>
        <div class="info">
          <p class="title">$judul</p>
          <div class="singer-duration">
            <p>$penyanyi</p>
            <p>&nbsp;&nbsp;&nbsp;</p>
            <p>$durasi</p>
          </div>
          <div class="genre-date">
            <p>$genre</p>
            <p>&nbsp;&nbsp;&nbsp;</p>
            <p>$tanggal</p>
          </div>
          <div class="audio-player">
            <audio controls>
              <source src="{$songPath}" type="audio/ogg" />
            </audio>
          </div>
        </div>
        <p>$tombol_album</p>
      </div>
  EOT;
  echo $html;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="css/song.css">
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Song name</title>
</head>

<body>

  <?php echoSongDetail($judul, $penyanyi, $tanggal, $genre, $durasi, $imagePath, $songPath, $tombol_album) ?>
  <form method="post" action="<?php echo 'song_edit.php?song_id=' . $_GET['song_id'] ?>">
    <?php if ($isAdmin) {
      echo '<button type="submit">edit button</button>';
    } else {
      echo '<button>bukan admin</button>';
    }
    ?>
  </form>
</body>

</html>