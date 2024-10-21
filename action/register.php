<?php
session_start();

// Подключение к базе данных
$connection = require_once "../connect/connect.php";
if (!$connection) {
    die('Ошибка подключения к базе данных.'); // Проверка подключения
}

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=register&error=invalid_request');
    exit();
}

// Функция для безопасного получения данных из POST
function getPostValue($key) {
    return htmlspecialchars(trim($_POST[$key] ?? ''));
}

// Получение данных из формы
$surname = getPostValue('surname');
$name = getPostValue('name');
$middleName = getPostValue('pathname');
$tel = getPostValue('tel');
$password = getPostValue('password');

// Сохраняем данные в сессии для повторного заполнения формы при ошибках
$_SESSION['form_data'] = compact('surname', 'name', 'middleName', 'tel');

// Инициализация массива для ошибок
$errors = [];

// Валидация номера телефона
if (empty($tel)) {
    $errors['tel'] = 'Заполните обязательное поле телефон!';
} elseif (!preg_match('/^[89]\d{10}$/', $tel)) {
    $errors['tel'] = 'Введите корректный номер телефона (пример: 80000000000)!';
} else {
    $stmt = $connection->prepare("SELECT * FROM user WHERE tel = ?");
    $stmt->execute([$tel]);
    if ($stmt->fetch()) {
        $errors['tel'] = 'Данный телефон уже зарегистрирован!';
    }
}

// Валидация пароля
if (empty($password)) {
    $errors['password'] = 'Заполните обязательное поле пароль!';
} elseif (strlen($password) < 3) {
    $errors['password'] = 'Минимальное количество символов в пароле — 3.';
}

// Если есть ошибки, сохраняем их и перенаправляем на страницу регистрации
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: ../index.php?page=register');
    exit();
}

// Хешируем пароль перед сохранением
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Выполняем запрос на добавление пользователя в базу данных
$stmt = $connection->prepare(
    "INSERT INTO user (surname, name, pathname, tel, password, role) 
    VALUES (?, ?, ?, ?, ?, ?)"
);

try {
    $stmt->execute([$surname, $name, $middleName, $tel, $hashedPassword, 'user']);
    // Сохраняем сообщение об успехе
    $_SESSION['success'] = 'Регистрация прошла успешно. Войдите в свой аккаунт.';
} catch (PDOException $e) {
    $_SESSION['errors']['db'] = 'Ошибка при выполнении запроса: ' . $e->getMessage();
    header('Location: ../index.php?page=register');
    exit();
}

// Очищаем данные сессии при успешной регистрации
unset($_SESSION['form_data'], $_SESSION['errors']);

// Перенаправляем на страницу входа
header('Location: ../index.php?page=sign');
exit();
