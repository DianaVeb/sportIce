<?php
session_start();

$connection = require_once "../connect/connect.php";
if (!$connection) {
    die('Ошибка подключения к базе данных.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=login&error=invalid_request');
    exit();
}

$tel = getPostValue('tel');
$password = getPostValue('password');
$_SESSION['form_data'] = compact('tel');


$errors = validateLoginInput($tel, $password);

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: ../index.php?page=sign');
    exit();
}

$user = authenticateUser($tel, $password, $connection);

if (!$user) {
    $_SESSION['errors']['auth'] = 'Неверный телефон или пароль!';
    header('Location: ../index.php?page=sign');
    exit();
}

$_SESSION['user'] = [
    'id' => $user['id'],
    'role' => $user['role']
];

$_SESSION['success'] = 'Вы успешно вошли в систему.';

unset($_SESSION['form_data'], $_SESSION['errors']);
header('Location: ../index.php?page=home');
exit();


function getPostValue($key)
{
    return htmlspecialchars(trim($_POST[$key] ?? ''));
}

function validateLoginInput($tel, $password)
{
    $errors = [];

    if (empty($tel)) {
        $errors['tel'] = 'Заполните обязательное поле телефон!';
    } elseif (!preg_match('/^[89]\d{10}$/', $tel)) {
        $errors['tel'] = 'Введите корректный номер телефона (пример: 80000000000)!';
    }

    if (empty($password)) {
        $errors['password'] = 'Заполните обязательное поле пароль!';
    }

    return $errors;
}

function authenticateUser($tel, $password, $connection)
{
    $stmt = $connection->prepare("SELECT * FROM user WHERE tel = ?");
    $stmt->execute([$tel]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user && password_verify($password, $user['password']) ? $user : false;
}
