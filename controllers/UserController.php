<?php

class UserController {
  private $db;

  public function __construct(DBController $db) {
    // if (!isset($db->con)) return null;
    $this->db = $db;
  }

  public function getUser($username) {
    $conn = $this->db->getConnection();
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user;
  }

  public function isUnique($value, $type) {
    $conn = $this->db->getConnection();
    $stmt = $conn->prepare("SELECT * FROM user WHERE " . $type . " = ?");
    $stmt->execute([$value]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
      return FALSE;
    }
    return TRUE;
  }

  public function isAdmin($isAdmin) {
    if ($isAdmin == 1) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  public function register($name, $email, $username, $password) {
    try {
      $conn = $this->db->getConnection();
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "INSERT INTO user (email, password, username, isAdmin, name)
      VALUES (:email, :password, :username, 0, :name)";
      $conn->prepare($sql)->execute(array(
        ":email" => $email,
        ":password" => password_hash($password, PASSWORD_DEFAULT),
        ":username" => $username,
        ":name" => $name
      ));
      echo "Account registered successfully!<br>";
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }

}