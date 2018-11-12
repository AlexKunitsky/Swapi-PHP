<?php
require_once 'core/init.php';

$people = new People();

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://swapi.co/api/people/",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

$response = json_decode($response, true); //because of true, it's in an array
//print_r($response);

foreach ($response as $key => $value) {

	foreach ($value as $result) {
		//print_r($result);
		//echo "<br>";
		//echo "<br>";

	}
}

curl_close($curl);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Index</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
</head>
<body>
  <div class="container" style="max-width: 560px;">
    <table id="myTable" class="display">
      <thead>
          <tr>
              <th>Column 1</th>
              <th>Column 2</th>
          </tr>
      </thead>
      <tbody>
          <tr>
              <td>Row 1 Data 1</td>
              <td>Row 1 Data 2</td>
          </tr>
          <tr>
              <td>Row 2 Data 1</td>
              <td>Row 2 Data 2</td>
          </tr>
      </tbody>
    </table>
  </div>
    
    <script>
        $(document).ready( function () {
        $('#myTable').DataTable();
        } );
  </script>
</body>
</html>