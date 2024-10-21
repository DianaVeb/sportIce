<?php
try {
    $dsn = 'mysql:host=localhost;dbname=SportIce;charset=utf8'; // Кодировка UTF-8
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Исключения при ошибках
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Массив как результат запроса
        PDO::ATTR_EMULATE_PREPARES => false, // Отключаем эмуляцию запросов
    ];

    $connection = new PDO($dsn, 'root', '', $options); // Подключение к БД
    return $connection; // Возвращаем объект PDO
} catch (PDOException $exception) {
    die('Ошибка подключения: ' . $exception->getMessage()); // Завершаем при ошибке
}