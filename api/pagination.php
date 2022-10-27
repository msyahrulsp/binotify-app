<?php
  require '../controllers/MainController.php';

  function getTotalPage($total_songs) {
    $total_pages = 1;
    while ($total_songs > 10) {
      $total_songs = $total_songs - 10;
      $total_pages += 1;
    }
    return $total_pages;
  };

  if (isset($_GET)) {
    $keyword = $_GET['keyword'];
    $genre = $_GET['genre'];
    $page = $_GET['page'];
    $limit = $_GET['limit'];
    $sort = $_GET['sort'];
    $order_type = $_GET['order_type'];
    $response = null;

    $songData = $song->searchSongs($keyword, $genre, (($page - 1) * $limit) + 1, $limit, $sort, $order_type);
    $total_songs = $song->countSongs($keyword, $genre);

    $total_page = getTotalPage($total_songs);

    $response['status'] = 200;
    $response['data'] = $songData;
    $response['page'] = $page;
    $response['total_page'] = $total_page;

    echo json_encode($response);
  }
?>