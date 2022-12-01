<?php
  require '../controllers/MainController.php';

  $subscription_controller = new SubscriptionController($db);

  $request_body = file_get_contents('php://input');

  if ($request_body) {
    $decoded_body = json_decode($request_body);
  
    $creator_id = $decoded_body->creator_id;
    $subscriber_id = $decoded_body->subscriber_id;
    $status = $decoded_body->status;
  
    $updateStatus = $subscription_controller->updateStatus($creator_id, $subscriber_id, $status);
    if ($updateStatus) {
      echo "Success";
    } else {
      echo "Failed";
    }
  } else {
    echo "Failed";
  }
?>