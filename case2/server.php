<?php

 // Генерация закрытого ключа
 $config = array(
    "config"=> "C:\wamp64\bin\apache\apache2.4.58\conf\openssl.cnf",
    "digest_alg" => "sha256",
    "private_key_bits" => 2048,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
);

$privateKey = openssl_pkey_new($config);

$details = openssl_pkey_get_details($privateKey);
 // Получаем открытый ключ
$publicKey = $details["key"]; 

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'getSignedMsg') {

//Генерируем случайное сообщение
$serverMsg = base64_encode(openssl_random_pseudo_bytes(16));

openssl_sign($serverMsg, $serverSignature, $privateKey, "sha256WithRSAEncryption");

$postData = [
'server_message' => $serverMsg,
'signature' => base64_encode($serverSignature),
'public_key' => base64_encode($publicKey)
];

echo json_encode($postData);

}

?>

