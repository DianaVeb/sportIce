<?php

// Запуск сессии
session_start();

global $connection;

// Подключение к БД
require_once '../connect/connect.php';

// Проверка на POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Поддерживается только метод POST');
}

// Получение данных из POST
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
// Убедимся, что цена - это число
$price = floatval($_POST['price'] ?? 0);
$file = $_FILES['path'] ?? null;
$category = intval($_POST['category_id'] ?? 0); // Должен быть целым числом
$type = intval($_POST['type_id'] ?? 0); // Должен быть целым числом
$generator = intval($_POST['generator_id'] ?? 0); // Должен быть целым числом

// Проверка типов файлов
$types = [
    "image/png",
    "image/jpg",
    "image/jpeg" 
];

if (empty($file['name'])) {
    $_SESSION['errors']['path'] = 'Загрузите файл';
} elseif ($file['size'] > 1024 * 1024 * 5) {
    $_SESSION['errors']['path'] = 'Максимальный размер 5 мб';
} elseif (!in_array($file['type'], $types)) {
    $_SESSION['errors']['path'] = 'Неверный формат картинки';
} else {
    $file_name = time() . '_' . basename($file['name']);
    $path = '../img/tovar/' . $file_name;
    $db_path = 'img/tovar/' . $file_name;

    if (!move_uploaded_file($file['tmp_name'], $path)) {
        $_SESSION['errors']['path'] = 'Не удалось загрузить файл';
        header('Location: ../index.php?page=edit'); // Убедитесь, что передаете правильный id
        exit();
    }

    // Подготовленный запрос для избежания SQL-инъекций
    $stmt = $connection->prepare("INSERT INTO products(name, description, price, foto, id_generator, id_categor, id_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        $_SESSION['errors']['database'] = 'Ошибка базы данных: ';
        header('Location: ../index.php?page=admin');
        exit();
    }

    // Привязываем параметры проверить типы
    $stmt->execute( [$name, $description, $price, $db_path, $generator, $category, $type]);

    if ($stmt->execute()) {
        header('Location: ../index.php?page=admin');
        exit();
    } else {
        $_SESSION['errors']['database'] = 'Ошибка выполнения запроса: ';
        header('Location: ../index.php?page=admin');
        exit();
    }
}
