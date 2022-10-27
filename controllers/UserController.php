<?php

class UserController {
  private $db;

  public function __construct(DBController $db) {
    // if (!isset($db->con)) return null;
    $this->db = $db;
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

  public function getAll() {
    $conn = $this->db->getConnection();
    $stmt = $conn->prepare("SELECT user_id, email, name, username FROM user WHERE isAdmin = 0");
    $stmt->execute();
    $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $user;
  }

  public function countUser() {
    $conn = $this->db->getConnection();
    $stmt = $conn->prepare("SELECT COUNT(*) as total_user from user WHERE isAdmin = 0");
    $stmt->execute();
    $total_user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $total_user['total_user'];
  }

  public function getUser($value, $type) {
    $conn = $this->db->getConnection();
    $stmt = $conn->prepare("SELECT * from user WHERE " . $type . " = ?");
    $stmt->execute([$value]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user;
  }

  public function isExist($value, $type) {
    $conn = $this->db->getConnection();
    $stmt = $conn->prepare("SELECT * from user WHERE " . $type . " = ?");
    $stmt->execute([$value]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
      return TRUE;
    }
    return FALSE;
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
      return TRUE;
    } catch (PDOException $e) {
      return FALSE;
    }
  }

}