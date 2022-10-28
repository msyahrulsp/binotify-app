<?php
define("DEFAULT_IMG", "../assets/images/defaultImage.jpg");
$songData = $song->getHomePageSongs();

function echoSongCard($index, $song)
{
  $song_id = $song['song_id'];
  $judul = $song['judul'];
  $penyanyi = $song['penyanyi'];    // nullable
  $tanggal = $song['tanggal_terbit']; // di spek mintanya tahun
  $genre = $song['genre'];          // nullable
  $duration = $song['duration'];
  $audioPath = $song['audio_path'];
  $imagePath = $song['image_path'] ?? DEFAULT_IMG; // nullable
  $album = $song['album_id'];       // nullable
  $html = <<<"EOT"
        <tr>
          <td class="rank">$index</td>
          <td>
            <div class="song-profile">
              <img src="$imagePath" width="60" height="60" />
              <div class="profile-text">
                <p class="title"><a href="/song.php?song_id={$song_id}">{$judul}</a></p>
                <p class="singer">{$penyanyi}</p>
              </div>
            </div>
          </td>
          <td class="date">{$tanggal}</td>
          <td class="genre">{$genre}</td>
        </tr>
  EOT;

  echo $html;
}
?>

<div class="song-list">
  <table>
    <tr>
      <th class="rank">#</th>
      <th>TITLE</th>
      <th class="date">DATE ADDED</th>
      <th class="genre">GENRE</th>
    </tr>
    <?php
    foreach ($songData as $key => $value) {
      echoSongCard($key + 1, $value);
    }
    ?>
  </table>
</div>