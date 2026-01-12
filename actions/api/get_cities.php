<?php
// actions/api/get_cities.php
error_reporting(0); // PENTING: Matikan error warning biar JSON bersih
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$apiKey = 'kgzVTT1de21043cd4150c339Idh6NLtl'; 
$search = isset($_GET['q']) ? $_GET['q'] : '';

if (empty($search)) {
    echo json_encode(['rajaongkir' => ['results' => []]]);
    exit;
}

$curl = curl_init();
$url = "https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?limit=100&search=" . urlencode($search);

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_SSL_VERIFYHOST => 0, 
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_HTTPHEADER => array("key: " . $apiKey),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    echo json_encode(['rajaongkir' => ['results' => []]]);
} else {
    $data = json_decode($response, true);
    $cleanResults = [];
    
    if (isset($data['data'])) {
        foreach ($data['data'] as $item) {
            $label = $item['city_name'];
            if (!empty($item['district_name'])) {
                $label .= " - " . $item['district_name'];
            }
            $cleanResults[] = [
                'city_id' => $item['id'],
                'type' => 'Kec',
                'city_name' => $label,
            ];
        }
    }
    echo json_encode(['rajaongkir' => ['results' => $cleanResults]]);
}
?>