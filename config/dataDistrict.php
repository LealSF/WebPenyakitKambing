<?php

$id_province = $_POST['id_province'];
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.rajaongkir.com/starter/city?&province=".$id_province,
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
    $dataDistrict = $array_response['rajaongkir']['results'];
    // print_r($array_response);
    echo "<option>--- Pilih Kabupaten ---</option>";
    foreach($dataDistrict as $key => $tiap_distrik){
        echo "<option value='".$tiap_distrik["type"]." ".$tiap_distrik["city_name"]."'>";
        echo $tiap_distrik['type']." ";
        echo $tiap_distrik['city_name'];
        echo "</option>";
    }
    echo "</pre>";
}