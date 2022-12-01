<?php
  require '../controllers/MainController.php';
  
  $subscription_controller = new SubscriptionController($db);

  if (isset($_GET)) {
    $user_id = $_GET['user_id'];

    $subscriptionStatuses = $subscription_controller->getSubscription($user_id);

    echo json_encode($subscriptionStatuses);
  }

?>