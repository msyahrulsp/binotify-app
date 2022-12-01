<?php

class SubscriptionController {
  public $db = null;

  public function __construct(DBController $db)
  {
    if (!isset($db->con)) return null;
    $this->db = $db;
  }

  public function getSubscription($userId) {
    if (isset($this->db->con)) {
      $stmt = $this->db->con->prepare("SELECT * FROM subscription WHERE subscriber_id = ?");
      $stmt->execute([$userId]);
      $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $subscriptions;
    } else {
      return FALSE;
    }
  }

  public function addNewSubscriber($creatorId, $userId) {
    if (isset($this->db->con)) {
      $conn = $this->db->getConnection();
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "INSERT INTO subscription (creator_id, subscriber_id, status)
      VALUES (:creator_id, :subscriber_id, :status)";
      $conn->prepare($sql)->execute(array(
        ":creator_id" => $creatorId,
        ":subscriber_id" => $userId,
        ":status" => "PENDING"
      ));
      return TRUE;
    } else {
      return FALSE;
    }
  }
}

?>