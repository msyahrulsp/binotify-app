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
  $destroySession = session_destroy();
  $logoutButton = null;
  if (!empty($_SESSION)) {
    $logoutButton = <<<"EOT"
    <a href="login.php" onclick="$destroySession">
      <nav class="nav-link">
        <img src=$logout alt="logout" />
        <text>Logout</text>
      </nav>
    </a>
    EOT;
  }

  if (!$isAdmin) {
    $html = <<<"EOT"
    <a href="index.php">
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
    $logoutButton
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
      $logoutButton
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