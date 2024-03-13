<!DOCTYPE html>

<html>

<head>
<title>Электронно-цифровая подпись</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<?php

if (isset($_GET['getSignedMessage'])) {

$response = file_get_contents('http://encoding/server.php?action=getSignedMsg');
$data = json_decode($response,true);

$message = $data['server_message'];
$signature = base64_decode($data['signature']);
//$signature = $data['signature'];
$publicKey = base64_decode($data['public_key']);

$verificationResult = openssl_verify($message, $signature, $publicKey, "sha256WithRSAEncryption");

echo "Статус верификации: ". ($verificationResult == 1 ? "Успешно" : "Ошибка");

}
?>

<div>
<form action = "" method="GET">
<button name="getSignedMessage" type="submit">Получить подписанное сообщение</button>
</form>
</div>
</body>
</html>