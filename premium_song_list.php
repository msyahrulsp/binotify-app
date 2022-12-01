<?php
require "controllers/MainController.php";

// @param {int} penyanyi_id GET req from clicked list penyanyi
$penyanyi_id = $_GET['penyanyi_id'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;
$base_rest_url = getenv('BASE_REST_URL');
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
  <link rel="icon" href="assets/images/component/spotify.png">
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
          </table>
        </section>
      </section>
    </section>
  </main>

  <script>
    let res = null
    let polledRes = null
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        if (res) {
          polledRes = JSON.parse(this.responseText)
          if (polledRes.toString() === res.toString()) {
            return
          } else {
            res = polledRes
          }
        } else {
          res = JSON.parse(this.responseText)
        }
        if (res) {
          let htmlRows = '<tr><th>No.</th><th>Judul</th><th>Music</th></tr>'
          res.forEach((song, idx) => {
            htmlRows = htmlRows.concat(`<tr style="background-color:#121212">
                <td class="rank">${idx+1}</td>
                <td style="max-width:10rem;white-space:normal; word-break:break-all;">
                  <div class="song-profile">
                    <div class="profile-text">
                      <p class="title"><a href="/song.php?song_id={el.song_id}">${song.judul}</a></p>
                    </div>
                  </div>
                </td>
                <td style="margin:auto; width:20rem; display:flex; justify-content:center;">
                  <audio controls preload="none">
                    <source src=${song.audio_path} type="audio/mpeg">
                  </audio>
                </td>
              </tr>`)
          })
          document.getElementById('premium-song-list').innerHTML = ""
          document.getElementById('premium-song-list').innerHTML = htmlRows
        } else {
          const notSubcribed = '<p>Kamu belum subscribe penyanyi.</p>'
          document.getElementById('song-list').innerHTML = notSubcribed
        }
      }
    };
    const baseRestURL = "<?php echo $base_rest_url ?>";
    const singerID = <?php echo $penyanyi_id ?>;
    const userID = <?php echo $user_id ?>;

    xhttp.open("GET", `${baseRestURL}/status/singer/${singerID}/user/${userID}`, true);
    xhttp.send();

    setInterval(() => {
      console.log("polling run");
      xhttp.open("GET", `${baseRestURL}/status/singer/${singerID}/user/${singerID}`, true);
      xhttp.send();
    }, 10000)
  </script>
</body>

</html>