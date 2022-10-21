<?php
class DBController
{
  private $con = null;

  public function __construct($host, $dbname, $user, $password)
  {
    try {
      $this->con =  new PDO(
        "mysql:host=" . $host . ";charset=" . 'utf8' . ";dbname=" . $dbname,
        $user,
        $password,
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

  public function getConnection() {
    return $this->con;
  }

  protected function closeConnection()
  {
    if ($this->con != null) {
      $this->con = null;
    }
  }
}
