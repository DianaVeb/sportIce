<?php
session_start();

$connection = require_once "../connect/connect.php";
if (!$connection) {
    die('Ошибка подключения к базе данных.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=register&error=invalid_request');
    exit();
}


$surname = getPostValue('surname');
$name = getPostValue('name');
$middleName = getPostValue('pathname');
$tel = getPostValue('tel');
$password = getPostValue('password');


$_SESSION['form_data'] = compact('surname', 'name', 'middleName', 'tel');


$errors = validateInput($tel, $password, $connection);

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: ../index.php?page=register');
    exit();
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

unset($_SESSION['form_data'], $_SESSION['errors']);

header('Location: ../index.php?page=sign');
exit();


function getPostValue($key)
{
    return htmlspecialchars(trim($_POST[$key] ?? ''));
}


function validateInput($tel, $password, $connection)
{
    $errors = [];

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

    if (empty($password)) {
        $errors['password'] = 'Заполните обязательное поле пароль!';
    } elseif (strlen($password) < 3) {
        $errors['password'] = 'Минимальное количество символов в пароле — 3.';
    }

    return $errors;
}

function registerUser($surname, $name, $middleName, $tel, $hashedPassword, $connection)
{
    $stmt = $connection->prepare(
        "INSERT INTO user (surname, name, pathname, tel, password, role) 
        VALUES (?, ?, ?, ?, ?, ?)"
    );

    try {
        $stmt->execute([$surname, $name, $middleName, $tel, $hashedPassword, 'user']);
        $_SESSION['success'] = 'Регистрация прошла успешно. Войдите в свой аккаунт.';
    } catch (PDOException $e) {
        $_SESSION['errors']['db'] = 'Ошибка при выполнении запроса: ' . $e->getMessage();
        header('Location: ../index.php?page=register');
        exit();
    }
}
