<?php

class SongController
{
  public $db = null;

  public function __construct(DBController $db)
  {
    if (!isset($db->con)) return null;
    $this->db = $db;
  }


  public function getSong()
  // fetch songs
  {
    $stmt = $this->db->con->prepare("SELECT * FROM `song`");
    $stmt->execute();
    $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $songs;
  }
}
