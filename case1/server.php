<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['client_signature']) && isset($_POST['client_message']) && isset($_POST['public_key'])) {
    
    $clientMessage = $_POST['client_message'];
    $clientSignature = base64_decode($_POST['client_signature']);
   // $clientSignature = $_POST['client_signature'];
    $publicKey = $_POST['public_key'];

    // Верификация подписи
    $verification = openssl_verify($clientMessage, $clientSignature, $publicKey, "sha256WithRSAEncryption");
    
    // Вывод статуса верификации
    echo $verification == 1 ? "Успешно" : "Ошибка";
} else {
    // Неправильный запрос
    http_response_code(400); // HTTP статус 400 Bad Request
    echo "Неправильный запрос";
}
?>
