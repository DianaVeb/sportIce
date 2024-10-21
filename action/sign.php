<?php
session_start();

// Подключение к базе данных
$connection = require_once "../connect/connect.php";
if (!$connection) {
    die('Ошибка подключения к базе данных.');
}

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=login&error=invalid_request');
    exit();
}

// Функция для безопасного получения данных из POST
function getPostValue($key) {
    return htmlspecialchars(trim($_POST[$key] ?? ''));
}

// Получение данных из формы
$tel = getPostValue('tel');
$password = getPostValue('password');

// Сохраняем данные в сессии для повторного заполнения формы при ошибках
$_SESSION['form_data'] = compact('tel');

// Инициализация массива для ошибок
$errors = [];

// Валидация номера телефона
if (empty($tel)) {
    $errors['tel'] = 'Заполните обязательное поле телефон!';
} elseif (!preg_match('/^[89]\d{10}$/', $tel)) {
    $errors['tel'] = 'Введите корректный номер телефона (пример: 80000000000)!';
}

// Валидация пароля
if (empty($password)) {
    $errors['password'] = 'Заполните обязательное поле пароль!';
}

// Если есть ошибки, перенаправляем обратно на страницу авторизации
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: ../index.php?page=sign');
    exit();
}

// Проверка существования пользователя в базе данных
$stmt = $connection->prepare("SELECT * FROM user WHERE tel = ?");
$stmt->execute([$tel]);
$user = $stmt->fetch(PDO::FETCH_ASSOC); // Используем PDO::FETCH_ASSOC для более удобного доступа

if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['errors']['auth'] = 'Неверный телефон или пароль!';
    header('Location: ../index.php?page=sign');
    exit();
}

// Успешная авторизация
$_SESSION['user'] = [
    'id' => $user['id'],
    'role' => $user['role']
]; // Сохраняем все данные пользователя в $_SESSION['user']

$_SESSION['success'] = 'Вы успешно вошли в систему.';

// Очищаем данные сессии при успешной авторизации
unset($_SESSION['form_data'], $_SESSION['errors']);

// Перенаправляем на главную страницу
header('Location: ../index.php?page=home');
exit();
