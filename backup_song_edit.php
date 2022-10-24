<?php
require('controllers/MainController.php');
echo '<br>';
echo $_GET['songID'];
$songData = $song->getSingleSong($_GET['songID']);
print_r($songData);
echo $songData['audio_path'];

$isEditing = $_SERVER['REQUEST_METHOD'] == "POST";
$isEditingID = $_POST['songID'];

$isAdmin = true;

$judul = $songData['judul'];
$penyanyi = $songData['penyanyi'];
$tanggal = $songData['tanggal_terbit'];
$genre = $songData['genre'];
$durasi = 0;
$imagePath = $songData['image_path'];
$songPath = $songData['audio_path'];
$tombol_album = 'ini tombol album';
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
  <form method="post">
    <input type="hidden" name="songID" value="<?php echo $_GET['songID'];?>">
    <?php if ($isAdmin && $isEditing) {
      echo '<button type="submit">simpan</button>';
    } elseif ($isAdmin) {
      echo '<button type="submit">edit</button>';
    }
    else {
      echo '<button>bukan admin</button>';
    }
    ?>
  </form>
  <?php $song->echoSongDetail($judul, $penyanyi, $tanggal, $genre, $durasi, $imagePath, $songPath, $tombol_album) ?>

</body>

</html>