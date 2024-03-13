<!DOCTYPE html>

<html>
<head>
    <title>Электронно-цифровая подпись</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<?php

 // Генерация закрытого ключа
    $config = array(
        "config"=> "C:\wamp64\bin\apache\apache2.4.58\conf\openssl.cnf",
        "digest_alg" => "sha256",
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    );

    $privateKey = openssl_pkey_new($config);

    // Получение публичного ключа из закрытого ключа
    $details = openssl_pkey_get_details($privateKey);
    $publicKey = $details["key"]; 

    // Обработка отправки формы
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['client_message'])) {

        $clientMessage = $_POST['client_message'];

        // Подписывание сообщения
        openssl_sign($clientMessage, $clientSignature, $privateKey, "sha256WithRSAEncryption");
        // Кодирование подписи для передачи
        $clientSignatureEncoded = base64_encode($clientSignature);

        // Отправка подписанного сообщения, подписи и открытого ключа на сервер
        $postData = http_build_query([
            'client_message' => $clientMessage,
            'client_signature' => $clientSignatureEncoded,
            'public_key' => $publicKey
        ]);

        $contextOptions = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded",
                'content' => $postData
            )
        );

        $context = stream_context_create($contextOptions);
        $response = file_get_contents('http://coding/server.php', false, $context);

        echo "Статус верификации: " . $response;
    }
    ?>

<div class="container">
        <form action="" method="POST">
        <h1>Работа с электронно-цифровой подписью</h1>
            <hr>
            <label for="client_message"><b>Введите сообщение</b></label>
            <input type="text" placeholder="Текст сообщения" name="client_message" required>
            <hr>
            <button type="submit">Подписать и отправить</button>
        </form>
    </div>
</body>
</html>
