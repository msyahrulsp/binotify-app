<?php
function echoNavbar() {
  $path = './assets/images/component/';
  $add = $path . 'add.png';
  $logo = $path . 'spotify.png';
  $home = $path . 'home.png';
  $list = $path . 'list.png';
  $logout = $path . 'logout.png';
  $isAdmin = $_SESSION['isAdmin'] ?? false;
  if ($isAdmin) {
    $html = <<<"EOT"
    <a href="index.php">
      <nav class="nav-logo">
        <img src=$logo alt="logo" />
        <text class="logo-text">Binotify</text>
      </nav>
    </a>
    <a href="index.php">
      <nav class="nav-link">
        <img src=$add alt="home" />
        <text>Tambah Lagu</text>
      </nav>
    </a>
    <a href="index.php">
      <nav class="nav-link">
        <img src=$add alt="list" />
        <text>Tambah Album</text>
      </nav>
    </a>
    <a href="index.php">
      <nav class="nav-link">
        <img src=$list alt="list" />
        <text>Daftar Album</text>
      </nav>
    </a>
    <a href="index.php">
      <nav class="nav-link">
        <img src=$logout alt="logout" />
        <text>Logout</text>
      </nav>
    </a>
    EOT;
  } else {
    $html = <<<"EOT"
      <a href="index.php">
        <nav class="nav-logo">
          <img src=$logo alt="logo" />
          <text class="logo-text">Binotify</text>
        </nav>
      </a>
      <a href="index.php">
        <nav class="nav-link">
          <img src=$home alt="home" />
          <text>Search</text>
        </nav>
      </a>
      <a href="index.php">
        <nav class="nav-link">
          <img src=$list alt="list" />
          <text>Daftar Album</text>
        </nav>
      </a>
      <a href="index.php">
        <nav class="nav-link">
          <img src=$logout alt="logout" />
          <text>Logout</text>
        </nav>
      </a>
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