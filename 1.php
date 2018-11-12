<?php
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
print_r($response);

foreach ($response as $key => $value) {
	if ($key == 'results') {
		# code...
	}
	print_r($key);
}

curl_close($curl);

