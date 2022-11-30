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
}

?>