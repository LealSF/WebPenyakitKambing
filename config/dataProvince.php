<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "key: 91f89f217a54da659876a02d2d8610b9"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
//   echo $response;
    $array_response = json_decode($response,TRUE);
    $dataProvince = $array_response['rajaongkir']['results'];

    // echo "<pre>";
    // print_r($dataProvince);
    // echo "</pre>";
    echo "<option>--- Pilih Provinsi ---</option>";
    foreach ($dataProvince as $key => $tiap_province){
        echo "<option value='".$tiap_province['province']."' id_province='".$tiap_province['province_id']."'>";
        echo $tiap_province['province'];
        echo "</option>";
    }
}