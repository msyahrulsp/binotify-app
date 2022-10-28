<?php

function echoNavbar() {
  $path = './assets/images/component/';
  $add = $path . 'add.png';
  $logo = $path . 'spotify.png';
  $home = $path . 'home.png';
  $search = $path . 'search.png';
  $list = $path . 'list.png';
  $logout = $path . 'logout.png';
  $isAdmin = $_SESSION['isAdmin'] ?? false;
  $authenticateButton = null;
  if (!empty($_SESSION)) {
    $authenticateButton = <<<"EOT"
    <a href="api/logout.php">
      <nav class="nav-link">
        <img src=$logout alt="logout" />
        <text>Logout</text>
      </nav>
    </a>
    EOT;
  } else {
    $authenticateButton = <<<"EOT"
    <a href="login.php">
      <nav class="nav-link">
        <img src=$logout alt="login" />
        <text>Login</text>
      </nav>
    </a>
    EOT;
  }

  if ($isAdmin) {
    $html = <<<"EOT"
    <a href="index.php" class="nav-logo-a">
      <nav class="nav-logo">
        <img src=$logo alt="logo" />
        <text class="logo-text">Binotify</text>
      </nav>
    </a>
    <a href="upload_song.php">
      <nav class="nav-link">
        <img src=$add alt="home" />
        <text>Tambah Lagu</text>
      </nav>
    </a>
    <a href="upload_album.php">
      <nav class="nav-link">
        <img src=$add alt="list" />
        <text>Tambah Album</text>
      </nav>
    </a>
    <a href="album_list.php">
      <nav class="nav-link">
        <img src=$list alt="list" />
        <text>Daftar Album</text>
      </nav>
    </a>
    <a href="user_list.php">
      <nav class="nav-link">
        <img src=$list alt="list" />
        <text>User List</text>
      </nav>
    </a>
    $authenticateButton
    EOT;
  } else {
    $html = <<<"EOT"
      <a href="index.php">
        <nav class="nav-logo">
          <img src=$logo alt="logo" />
          <text class="logo-text">Binotify</text>
        </nav>
      </a>
      <a href="search.php">
        <nav class="nav-link">
          <img src=$search alt="search" />
          <text>Search</text>
        </nav>
      </a>
      <a href="album_list.php">
        <nav class="nav-link">
          <img src=$list alt="list" />
          <text>Daftar Album</text>
        </nav>
      </a>
      $authenticateButton
    EOT;
  }
  echo $html;
}
?>

<div class="navbar">
  <?php
    echoNavbar();
  ?>
</div>