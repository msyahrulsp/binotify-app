<?php
  require "controllers/MainController.php";

  $userId = null;

  if (empty($_SESSION)) {
    echo "
      <script>
        alert('Silahkan login terlebih dahulu');
        window.location.href = '/login.php';
      </script>
    ";
  }

  if (!empty($_SESSION)) {
    if ($_SESSION['isAdmin']) {
      echo "
        <script>
          alert('User not authorized');
          window.location.href = '/'
        </script>
      ";
    }
    $userId = $_SESSION['user_id'];
  }

  // get subscribed
  $subscription = new SubscriptionController($db);

  $subscribed = $subscription->getSubscription($userId);

  $json_subscribed = json_encode($subscribed);

  $base_rest_url = getenv('BASE_REST_URL');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="css/search.css">
  <link rel="stylesheet" type="text/css" href="css/navbar.css">
  <link rel="icon" href="assets/images/component/spotify.png">
  <title>
    <?php
    echo 'Binotify · Penyanyi';
    ?>
  </title>
</head>
<body>
  <main class='container'>
    <?php
      include('templates/navbar.php');
    ?>
    <section class='content'>
      <header>
        <h2>Singer List</h2>
      </header>
      <section class='singer-list'>
        <table id='singer-list'>
          <tr>
            <th class='rank'>No.</th>
            <th class='singer-name'>NAME</th>
            <th class='subscribe'>SUBSCRIBE</th>
          </tr>
        </table>
      </section>
    </section>
  </main>

  <script>
    const userSubscribed = <?php echo $json_subscribed ?>;
    let singers = null;

    function returnStatus(status) {
      if (status === null) {
        return 'Subscribe';
      } else if (status === 'PENDING') {
        return 'Pending';
      } else if (status === 'ACCEPTED') {
        return 'Go To Song';
      } else if (status === 'REJECTED') {
        return 'REJECTED';
      }
    }

    function getSingers() {
      const xhttp = new XMLHttpRequest();
      const baseRestURL = "<?php echo $base_rest_url ?>";
      const singerAPI = baseRestURL + "/singers";
      const singer_list = document.getElementById('singer-list')
      const user_id = <?php echo $userId ?>;
      const curr_date = new Date().toISOString().replace(/T/, " ").replace(/\..+/, "");

      xhttp.onload = function () {
        if (this.readyState === 4 && this.status === 200) {
          const response = JSON.parse(this.responseText);
          console.log(response);
          singers = response;
          const result = response.data.map((singer, index) => {
            const name = singer.name;
            const singer_id = singer.user_id;
            const subscription = userSubscribed.find((sub) => parseInt(sub.creator_id) === singer_id);
            let status = null;
            if (subscription) {
              status = subscription.status;
            }
            return (
              `
              <tr key='${singer_id}'}>
                <td class='rank'>${index + 1}</td>
                <td class='singer-name'>${name}</td>
                <td class='subscribe subscribe-content' key=${singer_id}>
                  <a id='subscribe-link' href=${status === 'ACCEPTED' ? `/premium_song_list.php?penyanyi_id=${singer_id}` : '#'}>
                    <button id='subscribe-button' class='subscribe-button ${status === 'PENDING' ? 'pending' : status === 'REJECTED' ? 'reject' : 'neutral'}' onclick="${status === null ? `subscribe(${singer_id}, ${user_id}, '${curr_date}')` : ''}">${returnStatus(status)}</button>
                  </a>
                </td>
              </tr>
              `
            )
          })
          result.unshift(`
          <tr>
              <th class='rank'>No.</th>
              <th class='singer-name'>NAME</th>
              <th class='subscribe'>SUBSCRIBE</th>
            </tr>
          `)
          singer_list.innerHTML = result.join('')
        }
      }
      xhttp.open("GET", singerAPI);
      xhttp.send();
    }

    function subscribe(creatorId, userId, currDate) {
      const xhttp = new XMLHttpRequest();
      const fd = new FormData();
      fd.append('user_id', userId);
      fd.append('creator_id', creatorId);
      fd.append('curr_date', currDate);
      xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
          const response = this.responseText;
          if (response) {
            location.reload()
          }
        }
      }
      xhttp.open("POST", 'api/subscribe.php', true);
      xhttp.send(fd);
    }

    getSingers();

    function pollingStatus() {
      const xhttp = new XMLHttpRequest();
      const user_id = <?php echo $userId ?>;
      const subscribe_content = document.getElementsByClassName('subscribe-content');

      xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
          const response = JSON.parse(this.responseText);
          if (response) {
            const singersData = singers.data
            singersData.forEach((singer) => {
              const singer_id = singer.user_id;
              for (let subscribe_el of subscribe_content) {
                const key = subscribe_el.getAttribute("key")
                if (singer_id === parseInt(key)) {
                  if (response.find((res) => res.creator_id === key)) {
                    const status = response.find((res) => res.creator_id === key).status
                    subscribe_el.innerHTML = `
                      <a id='subscribe-link' href=${status === 'ACCEPTED' ? `/premium_song_list.php?penyanyi_id=${singer_id}` : '#'}>
                        <button id='subscribe-button' class='subscribe-button ${status === 'PENDING' ? 'pending' : status === 'REJECTED' ? 'reject' : 'neutral'}'}">${returnStatus(status)}</button>
                      </a>
                    `
                  }
                }
              }
            })
          }
        }
      }
      xhttp.open("GET", `api/check_subscription_status.php?user_id=${user_id}`);
      xhttp.send();
    }

    setInterval(() => {
      pollingStatus();
    }, 15000)

  </script>
</body>
</html>

