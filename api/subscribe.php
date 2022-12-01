<?php
  require '../controllers/MainController.php';

  // SOAP request
  $subscription_controller = new SubscriptionController($db);
  $api_key = getenv('SOAP_API_KEY');
  $base_soap_url = getenv('BASE_SOAP_URL');
  $app_address = getenv('APP_ADDRESS');

  if (isset($_POST)) {
    $creator_id = $_POST['creator_id'];
    $user_id = $_POST['user_id'];
    $curr_date = $_POST['curr_date'];

    // SOAP request subscription
    $webservice_url = $base_soap_url . '/subscribe?wsdl';

    $request_param = '
      <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:int="http://interfaces.binotify.com/">
        <soapenv:Header/>
        <soapenv:Body>
          <int:newSubscribe>
              <creator_id>' . $creator_id . '</creator_id>
              <subscriber_id>' . $user_id . '</subscriber_id>
          </int:newSubscribe>
        </soapenv:Body>
      </soapenv:Envelope>
    ';

    $headers = array(
      'Authorization: Basic ' . $api_key,
      'Content-Type: "text/xml"',
      'X-Forwarded-For: ' . $app_address,
      'Date: ' . $curr_date
    );

    $ch = curl_init($webservice_url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request_param);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $data = curl_exec($ch);

    $result = $data;

    if ($result === FALSE) {
      printf("CURL error (#%d): %s<br>\n", curl_errno($ch),
      htmlspecialchars(curl_error($ch)));
    }
    curl_close($ch);

    $xmlDoc = new DOMDocument();
    $xmlDoc->loadXML($data);

    $xmlObject = $xmlDoc->documentElement;
    $response = $xmlObject->nodeValue;

    if ($response == 'true') {
      $subscription_status = $subscription_controller->addNewSubscriber($creator_id, $user_id);
      if ($subscription_status) {
        echo TRUE;
      } else {
        echo FALSE;
      }
    } else {
      echo FALSE;
    }
  }
?>