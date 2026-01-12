<?php
// actions/api/get_cost.php
error_reporting(0);
header('Content-Type: application/json');

$apiKey = 'kgzVTT1de21043cd4150c339Idh6NLtl'; 
$origin = '419'; // Cicendo Bandung

$destination = $_POST['destination'] ?? '';
$weight = 1000; 
$courier = 'jne'; 

if (!$destination) {
    echo json_encode(['rajaongkir' => ['results' => []]]);
    exit;
}

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => http_build_query([
      'origin' => $origin,
      'destination' => $destination,
      'weight' => $weight,
      'courier' => $courier
  ]),
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_HTTPHEADER => array("content-type: application/x-www-form-urlencoded", "key: " . $apiKey),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    echo json_encode(['status' => 'error']);
} else {
    $data = json_decode($response, true);
    $costs = [];
    $serviceList = $data['data'] ?? [];
    
    foreach ($serviceList as $svc) {
        // Cari harga di semua kemungkinan key
        $harga = 0;
        if (!empty($svc['price'])) $harga = $svc['price'];
        elseif (!empty($svc['cost'])) $harga = $svc['cost'];
        elseif (!empty($svc['value'])) $harga = $svc['value'];
        elseif (!empty($svc['tariff'])) $harga = $svc['tariff'];

        $costs[] = [
            'service' => $svc['service_code'] ?? 'REG',
            'cost' => [['value' => floatval($harga), 'etd' => $svc['etd'] ?? '-']]
        ];
    }

    echo json_encode([
        'rajaongkir' => [
            'results' => [['costs' => $costs]]
        ]
    ]);
}
?>