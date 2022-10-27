<?php
define("DEFAULT_IMG", "../assets/images/defaultImage.jpg");
$songData = $song->getHomePageSongs();

function echoSongCard($song)
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
      <a href="google.com">
        <tr>
          <td class="rank">1</td>
          <td>
            <div class="song-profile">
              <img src="$imagePath" width="50" height="50" />
              <div class="profile-text">
                <p class="title"><a href="/song.php?song_id={$song_id}">{$judul}</a></p>
                <p class="singer">{$penyanyi}</p>
              </div>
            </div>
          </td>
          <td>{$tanggal}</td>
          <td>{$genre}</td>
        </tr></a>
  EOT;

  echo $html;
}
?>

<div>
  <h1>Top 10 Songs</h1>
  <div>
    <table>
      <tr>
        <th class="rank">#</th>
        <th>TITLE</th>
        <th>DATE ADDED</th>
        <th>GENRE</th>
      </tr>
      <?php
      foreach ($songData as $row) {
        echoSongCard($row);
      }
      ?>
    </table>
  </div>
</div>