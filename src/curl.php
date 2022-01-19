<?php

$url = "http://vervalyayasan.data.kemdikbud.go.id/index.php/Chome/profil?yayasan_id=4F3B660E-F82B-4763-A214-80CA778A7B02";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
curl_close($curl);
var_dump($resp);

?>

