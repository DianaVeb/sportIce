<?php
session_start();
global $connection;

require_once '../connect/connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Поддерживается только метод POST');
}

$surname = getPostValue('surname');
$name = getPostValue('name');
$middleName = getPostValue('pathname');
$oldPassword = getPostValue('old_password');
$newPassword = getPostValue('new_password');
$id = $_SESSION['user']['id'];

$errors = validateInput($surname, $name, $middleName, $oldPassword, $connection, $id);

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    
    $_SESSION['user']['surname'] = $surname;
    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['pathname'] = $middleName;
    
    header('Location: ../index.php?page=admin');
    exit();
}

try {
    $stmt = $connection->prepare("SELECT password FROM user WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($oldPassword, $user['password'])) {
        $_SESSION['errors']['password'] = 'Неверный старый пароль.';
        
        $_SESSION['user']['surname'] = $surname;
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['pathname'] = $middleName;

        header('Location: ../index.php?page=admin');
        exit();
    }

    $hashedPassword = !empty($newPassword) ? password_hash($newPassword, PASSWORD_DEFAULT) : $user['password'];

    $stmt = $connection->prepare("UPDATE user SET surname = ?, name = ?, pathname = ?, password = ? WHERE id = ?");
    if ($stmt->execute([$surname, $name, $middleName, $hashedPassword, $id])) {
        unset($_SESSION['user']['surname']);
        unset($_SESSION['user']['name']);
        unset($_SESSION['user']['pathname']);
        header('Location: ../index.php?page=admin');
        exit();
    } else {
        $_SESSION['errors']['database'] = 'Ошибка выполнения запроса';
        header('Location: ../index.php?page=admin');
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['errors']['database'] = 'Ошибка базы данных: ' . $e->getMessage();
    header('Location: ../index.php?page=admin');
    exit();
}

function getPostValue($key)
{
    return htmlspecialchars(trim($_POST[$key] ?? ''));
}

function validateInput($surname, $name, $oldPassword)
{
    $errors = [];

    if (empty($surname)) {
        $errors['surname'] = 'Заполните обязательное поле фамилия!';
    }

    if (empty($name)) {
        $errors['name'] = 'Заполните обязательное поле имя!';
    }


    if (empty($oldPassword)) {
        $errors['password'] = 'Заполните обязательное поле старый пароль!';
    } elseif (strlen($oldPassword) < 3) {
        $errors['password'] = 'Минимальное количество символов в пароле — 3.';
    }

    return $errors;
}
