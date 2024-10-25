<?php
session_start();
global $connection;

require_once '../connect/connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Поддерживается только метод POST');
}

$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = floatval($_POST['price'] ?? 0);
$file = $_FILES['path'] ?? null;
$category = intval($_POST['category_id'] ?? 0);
$type = intval($_POST['type_id'] ?? 0);
$generator = intval($_POST['generator_id'] ?? 0);

$types = ["image/png", "image/jpg", "image/jpeg"];
$errors = [];


if (empty($name)) {
    $errors['name'] = 'Введите название товара';
} elseif (strlen($name) > 255) {
    $errors['name'] = 'Название товара не должно превышать 255 символов';
}

if (empty($description)) {
    $errors['description'] = 'Введите описание товара';
} elseif (strlen($description) > 1000) {
    $errors['description'] = 'Описание товара не должно превышать 1000 символов';
}

if ($price <= 0) {
    $errors['price'] = 'Введите корректную цену товара';
}

if (empty($file['name'])) {
    $errors['path'] = 'Загрузите файл';
} elseif ($file['size'] > 1024 * 1024 * 5) {
    $errors['path'] = 'Максимальный размер 5 мб';
} elseif (!in_array($file['type'], $types)) {
    $errors['path'] = 'Неверный формат картинки. Доступные форматы: PNG, JPG, JPEG';
} else {
    $file_name = time() . '_' . basename($file['name']);
    $path = '../img/tovar/' . $file_name;
    $db_path = 'img/tovar/' . $file_name;

    if (!move_uploaded_file($file['tmp_name'], $path)) {
        $errors['path'] = 'Не удалось загрузить файл. Попробуйте еще раз.';
    }
}

if ($category <= 0) {
    $errors['category'] = 'Выберите категорию';
}

if ($type <= 0) {
    $errors['type'] = 'Выберите тип';
}

if ($generator <= 0) {
    $errors['generator'] = 'Выберите производителя';
}


if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = [
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'category_id' => $category,
        'type_id' => $type,
        'generator_id' => $generator
    ];
    header('Location: ../index.php?page=edit');
    exit();
}

$stmt = $connection->prepare("INSERT INTO products(name, description, price, foto, id_generator, id_categor, id_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
if ($stmt === false) {
    $_SESSION['errors']['database'] = 'Ошибка базы данных';
    header('Location: ../index.php?page=admin');
    exit();
}

if ($stmt->execute([$name, $description, $price, $db_path, $generator, $category, $type])) {
    header('Location: ../index.php?page=admin');
    exit();
} else {
    $_SESSION['errors']['database'] = 'Ошибка выполнения запроса';
    header('Location: ../index.php?page=admin');
    exit();
}
?>
