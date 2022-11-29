<?php
require "controllers/MainController.php";

// @param {int} penyanyi_id GET req from clicked list penyanyi
$penyanyi_id = $_GET['penyanyi_id'] ?? 1;
$user_id = $_SESSION['user_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="css/search.css">
  <link rel="stylesheet" type="text/css" href="css/navbar.css">
  <link rel="stylesheet" type="text/css" href="css/premium_song.css">
  <title>
    <?php
    echo 'Binotify · Lagu Premium';
    ?>
  </title>
</head>

<body>
  <main class="container">
    <?php
    include('templates/navbar.php');
    ?>
    <section class="content-container">
      <section class="content">
        <header>
          <h2 class="page-title">List Lagu Premium</h2>
          <!-- <h2><? echo $user_id ?> <? echo $penyanyi_id ?></h2> -->
        </header>
        <section class="song-list" id="song-list">
          <table id="premium-song-list">
            <tr>
              <th>No.</th>
              <th>Judul</th>
              <th>Penyanyi</th>
              <th>Audio Path</th>
            </tr>
          </table>
        </section>
      </section>
    </section>
  </main>

  <script>
    let res = null
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        res = JSON.parse(this.responseText)
        if (res) {
          let htmlRows = ''
          res.forEach((song, idx) => {
            htmlRows = htmlRows.concat(`<tr>
                <td class="rank">${idx}</td>
                <td>
                  <div class="song-profile">
                    <div class="profile-text">
                      <p class="title"><a href="/song.php?song_id={el.song_id}">${song.judul}</a></p>
                    </div>
                  </div>
                </td>
                <td>${song.penyanyi_id}</td>
                <td>${song.audio_path}</td>
              </tr>`)
          })
          document.getElementById('premium-song-list').insertAdjacentHTML("beforeend", htmlRows);
        } else {
          const notSubcribed = '<p>Kamu belum subscribe penyanyi.</p>'
          document.getElementById('song-list').innerHTML = notSubcribed
        }
      }
    };
    const baseRestURL = 'http://0.0.0.0:3002';
    const singerID = <?php echo $penyanyi_id ?>;
    const userID = <?php echo $user_id ?>;
    xhttp.open("GET", `${baseRestURL}/status/singer/${singerID}/user/${singerID}`, true);
    xhttp.send();
  </script>
</body>

</html>