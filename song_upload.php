<?php
require 'controllers/MainController.php';
$songData = $song->getSongs();
$lastSongID = $song->getLastSongID();
// foreach ($songData as $row) {
//   echo $row['judul'];
// }

// function 
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <form action="song_uploaded.php" method="post" enctype="multipart/form-data">
    <!-- auto count duration -->
    Judul:
    <input type="text" name='judul'><br>
    Penyanyi:
    <input type="text" name='penyanyi'><br>
    Tanggal:
    <input type="date" name='tanggal'><br>
    Genre:
    <input type="text" name='genre'><br>
    File lagu:
    <input type="file" name="songToUpload" id="songToUpload"><br>
    File gambar:
    <input type="file" name="imageToUpload" id="imageToUpload"><br>
    <input type="text" name="lastSongID" value=<?php echo $lastSongID ?>>
    Album:
    <span>TBD s input selection for available album</span><br>
    <button type="submit" value="Upload Song" name="submit">Upload Song</button>
  </form>
</body>

</html>