<?php
  require "controllers/MainController.php";


  if (empty($_SESSION)) {
    echo "
      <script>
        alert('Silahkan login terlebih dahulu');
        window.location.href = '/login.php';
      </script>
    ";
  }

  $total_songs = $song->countSongs('', '');
  $total_page = 1;
  while ($total_songs > 10) {
    $total_songs = $total_songs - 10;
    $total_page += 1;
  }
  $songs = $song->searchSongs('', '', 1, 10, 'judul', 'asc');
  
  function createPagination($page) {
    $html = <<<"EOT"
      <p onclick="changePage(this.innerText)">{$page}</p>
    EOT;

    echo $html;
  }

  function echoSongCard($song, $index)
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
                <img src="$imagePath" width="50" height="50" />
                <div class="profile-text">
                  <p class="title"><a href="/song.php?song_id={$song_id}">{$judul}</a></p>
                  <p class="singer">{$penyanyi}</p>
                </div>
              </div>
            </td>
            <td>{$tanggal}</td>
            <td>{$genre}</td>
          </tr>
    EOT;

    echo $html;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="css/search.css">
  <link rel="stylesheet" type="text/css" href="css/navbar.css">
  <title>
    <?php
      echo 'Binotify · Search';
    ?>
  </title>
</head>
<body>
  <main class="container">
    <?php
      include('templates/navbar.php');
    ?>
    <section class="content">
      <header>
        <h2>Search</h2>
      </header>
      <input type="text" placeholder="What do you want to listen to?" name="search" id="search" onkeyup="debounceInput(this.value)" class="input__search" />
      <select name="genre" id="genre" class="select-genre" onchange="onSelectGenre(this.value)">
        <option value="" selected>All Genre</option>
        <option value="Pop">Pop</option>
        <option value="Rock">Rock</option>
        <option value="Blues">Blues</option>
        <option value="Electronic">Electronic</option>
        <option value="Classic">Classic</option>
        <option value="Sedih">Sedih</option>
      </select>
      <section class="song-list">
        <table id="song-list">
          <tr>
            <th class="rank">No.</th>
            <th class="sort" onclick="sortOrder('judul')">TITLE</th>
            <th class="sort" onclick="sortOrder('tanggal_terbit')">DATE ADDED</th>
            <th>GENRE</th>
          </tr>
          <?php
            for ($i = 1; $i <= 10; $i++) {
              echoSongCard($songs[$i-1], $i);
            }
          ?>
        </table>
      </section>
      <section class="pagination" id="pagination">
        <?php
          for ($i = 1; $i <= $total_page; $i++) {
            createPagination($i);
          }
        ?>
      </section>
    </section>
  </main>

  <script>
    const song_list = document.getElementById('song-list');
    const pagination = document.getElementById('pagination');
    const judul = document.getElementById('judul');
    const tanggal_terbit = document.getElementById('tanggal_terbit');
    let genre = '';
    let current_page = 1;
    let sort = {
      'type': 'judul',
      'judul_asc': true,
      'tanggal_asc': false,
    }
    let keyword = ''

    function getOrderType(asc) {
      if (asc) {
        return 'asc';
      } else {
        return 'desc';
      }
    }

    function sortOrder(type) {
      sort.type = type;
      if (type === 'judul') {
        sort.judul_asc = !sort.judul_asc;
        sort.tanggal_asc = false;
      } else {
        sort.tanggal_asc = !sort.tanggal_asc;
        sort.judul_asc = false;
      }
      changePage(current_page);
    }

    function onSelectGenre(genreValue) {
      genre = genreValue;
      search(keyword);
    }
    

    function debounce(func, timeout) {
      let timer;
      return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => { func.apply(this, args) }, timeout);
      }
    }

    function search(key) {
      keyword = key;
      const xhttp = new XMLHttpRequest()
      xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
          const response = JSON.parse(this.responseText);
          const result = response.data.map((el, index) => {
            return (
            `<a href="google.com">
              <tr>
                <td class="rank">${index + 1}</td>
                <td>
                  <div class="song-profile">
                    <img src="${el.image_path}" width="50" height="50" />
                    <div class="profile-text">
                      <p class="title"><a href="/song.php?song_id=${el.song_id}">${el.judul}</a></p>
                      <p class="singer">${el.penyanyi}</p>
                    </div>
                  </div>
                </td>
                <td>${el.tanggal_terbit}</td>
                <td>${el.genre}</td>
              </tr>
            </a>`
            )
          })
          result.unshift(`<tr>
          <th class="rank">No.</th>
          <th class="sort" onclick="sortOrder('judul')">TITLE</th>
          <th class="sort" onclick="sortOrder('tanggal_terbit')">DATE ADDED</th>
          <th>GENRE</th>
          </tr>`)
          const pages = []
          for (let page = 1; page <= response.total_page; page++) {
            pages.push(`<p onclick="changePage(this.innerText)">${page}</p>`);
          }
          song_list.innerHTML = result.join('');
          pagination.innerHTML = pages.join('');
        }
      }
      xhttp.open("GET",`api/search.php?keyword=${key}&genre=${genre}&page=1&limit=10&sort=${sort.type}&order_type=${getOrderType(sort.type === 'judul' ? sort.judul_asc : sort.tanggal_asc)}`, true);
      xhttp.send();
    }

    const debounceInput = debounce((key) => search(key, genre), 500);

    function changePage(page) {
      current_page = parseInt(page);
      const xhttp = new XMLHttpRequest()
      xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
          const response = JSON.parse(this.responseText);
          const result = response.data.map((el, index) => {
            return (
            `<a href="google.com">
              <tr>
                <td class="rank">${index + 1}</td>
                <td>
                  <div class="song-profile">
                    <img src="${el.image_path}" width="50" height="50" />
                    <div class="profile-text">
                      <p class="title"><a href="/song.php?song_id=${el.song_id}">${el.judul}</a></p>
                      <p class="singer">${el.penyanyi}</p>
                    </div>
                  </div>
                </td>
                <td>${el.tanggal_terbit}</td>
                <td>${el.genre}</td>
              </tr>
            </a>`
            )
          })
          result.unshift(`<tr>
          <th class="rank">No.</th>
          <th class="sort" onclick="sortOrder('judul')">TITLE</th>
          <th class="sort" onclick="sortOrder('tanggal_terbit')">DATE ADDED</th>
          <th>GENRE</th>
          </tr>`)
          const pages = []
          for (let page = 1; page <= response.total_page; page++) {
            pages.push(`<p onclick="changePage(this.innerText)">${page}</p>`);
          }
          song_list.innerHTML = result.join('');
          pagination.innerHTML = pages.join('');
        }
      }
      xhttp.open("GET",`api/pagination.php?keyword=${keyword}&genre=${genre}&page=${page}&limit=10&sort=${sort.type}&order_type=${getOrderType(sort.type === 'judul' ? sort.judul_asc : sort.tanggal_asc)}`, true);
      xhttp.send();
    }

  </script>
</body>
</html>