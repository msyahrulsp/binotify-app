<?php
require "controllers/MainController.php";

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
    const xhttp = new XMLHttpRequest();
    const baseRestURL = "<?php echo $base_rest_url ?>";
    const singerAPI = baseRestURL + "/singers";
    const singer_list = document.getElementById('singer-list')


    xhttp.onload = function () {
      if (this.readyState === 4 && this.status === 200) {
        const response = JSON.parse(this.responseText);
        const result = response.data.map((singer, index) => {
          const name = singer.name;
          const user_id = singer.user_id;
          return (
            `
            <tr key='${user_id}'}>
              <td class='rank'>${index + 1}</td>
              <td class='singer-name'>${name}</td>
              <td class='subscribe'><button class='subscribe-button'>Subscribe</button></td>
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

  </script>
</body>
</html>

