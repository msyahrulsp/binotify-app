<?php
define("DEFAULT_IMG", "./assets/images/defaultImage.jpg");
$songData = $song->getSong();


function echoSongCard($song)
{
  // judul, penyanyi, tahun, genre, durasi, audio path, image path,  album terkait dari lagu
  $judul = $song['judul'];
  $penyanyi = $song['penyanyi'];    // nullable
  $tanggal = $song['tanggal_terbit']; // di spek mintanya tahun
  $genre = $song['genre'];          // nullable
  $duration = $song['duration'];
  $audioPath = $song['audio_path'];
  $imagePath = $song['image_path'] ?? DEFAULT_IMG; // nullable
  $album = $song['album_id'];       // nullable
  $html = <<<"EOT"
  <div>
    <img src={$imagePath} width=100 height=100>
    <p>{$judul}</p>
    <p>{$penyanyi}</p>
    <p>{$tanggal}</p> 
    <p>{$genre}</p>
    <p>-----------------</p>
  </div>
  EOT;

  echo $html;
}
?>

<div>
  <h1>Top 10 Songs</h1>
  <div>
    <?php
    foreach ($songData as $row) {
      echoSongCard($row);
    }
    ?>
  </div>
</div>