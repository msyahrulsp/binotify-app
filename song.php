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
  <div>
    <p>$judul</p>
    <p>$penyanyi</p>
    <p>$tanggal</p>
    <p>$genre</p>
    <p>$durasi</p>
    <img src="{$imagePath}" height=100 width=100>
  <audio controls>
    <source src="{$songPath}" type="audio/ogg" />
  </audio>
    <p>$tombol_album</p>
  </div>
  EOT;
  echo $html;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Song name</title>
</head>

<body>
  <h1>$judul</h1>
  <form method="post" action="<?php echo 'song_edit.php?song_id='.$_GET['song_id']?>">
    <?php if ($isAdmin) {
      echo '<button type="submit">edit</button>';
    }
    else {
      echo '<button>bukan admin</button>';
    }
    ?>
  </form>
  <?php echoSongDetail($judul, $penyanyi, $tanggal, $genre, $durasi, $imagePath, $songPath, $tombol_album) ?>

</body>

</html>