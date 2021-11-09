<?php

function getIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arIp = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = $arIp[0];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

$data = [
    "stream_id" => $_POST["stream_id"],// Берем из раздела "Потоки" столбец ID и добавляем в форму создания заказа
    "name" => $_POST["name"],
    "phone" => $_POST["phone"],
    "ip" => getIp(),
    "geo" => $_POST["geo"],// Берем из раздела "Потоки" столбец Geo и добавляем в форму создания заказа Пример: UA
    "ua" => $_SERVER["HTTP_USER_AGENT"],
    "referer" => $_SERVER["HTTP_REFERER"]
];

$apiKey = "d025e4d6462b991ccc2448d9116ddfd6";// Заменяем на свой из кабинета в разделе профиль

//CURL BLOCK
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://cpa-bond.com/api/v1/new-lead");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
$headers[] = 'Authorization: Bearer ' . $apiKey;
$headers[] = 'Content-Type: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
if (isset($data['ua'])) {
    curl_setopt($ch, CURLOPT_USERAGENT, $data['ua']);
}
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$response = curl_exec($ch);
curl_close($ch);

$response = json_decode($response, true);

if ($response['status'] == 'ok') {
    $leadId = $response['lead_id'];
    header("Location: ../success.html");
} else {
    $messageError = $response['message'];
}