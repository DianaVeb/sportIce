<?php
try {
    $dsn = 'mysql:host=localhost;dbname=SportIce;charset=utf8'; 
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $connection = new PDO($dsn, 'root', '', $options);
    return $connection;
} catch (PDOException $exception) {
    die('Ошибка подключения: ' . $exception->getMessage());
}