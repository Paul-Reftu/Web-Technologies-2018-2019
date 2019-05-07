<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://safebrowsing.googleapis.com/v4/threatMatches:find?key=AIzaSyAN9Nc3okzr3_1rDHn5oSMj86bneLoCzl0",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "  {\"client\": {
  \"clientId\":      \"tehnologiiweb2019\",
  \"clientVersion\": \"1.5.2\"
},
\"threatInfo\": {
  \"threatTypes\":      [\"MALWARE\", \"SOCIAL_ENGINEERING\"],
  \"platformTypes\":    [\"WINDOWS\"],
  \"threatEntryTypes\": [\"URL\"],
  \"threatEntries\": [
    {\"url\": \"http://www.urltocheck.org/\"}
  ]
}
  }",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
    "postman-token: b05b8d34-85f2-49cf-0f8e-03686a71e4e9"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

?>