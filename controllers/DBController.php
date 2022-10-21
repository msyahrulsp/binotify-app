<?php

class DBController
{
  protected $host = 'localhost';
  protected $user = 'root';
  protected $password = '';
  protected $dbname = 'binotify';

  public $con = null;

  public function __construct()
  {
    try {
      $this->con =  new PDO(
        "mysql:host=" . $this->host . ";charset=" . 'utf8' . ";dbname=" . $this->dbname,
        $this->user,
        $this->password,
        [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_NAMED
        ]
      );
    } catch (Exception $ex) {
      exit($ex->getMessage());
    }
  }

  public function __destruct() {
    $this->closeConnection();
  }

  protected function closeConnection()
  {
    if ($this->con != null) {
      $this->con = null;
    }
  }
}
