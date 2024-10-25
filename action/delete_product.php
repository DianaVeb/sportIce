<?php
session_start();

global $connection;

require_once '../connect/connect.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php?page=home');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['confirm']) && $_POST['confirm'] === 'yes') {
    $productId = (int)$_POST['id'];

    $stmt = $connection->prepare("DELETE FROM products WHERE id = ?");
    
    if ($stmt->execute([$productId])) {
        $_SESSION['success'] = "Продукт успешно удален.";
    } else {
        $_SESSION['errors'] = "Ошибка при удалении продукта.";
    }
    
    header('Location: ../index.php?page=admin');
    exit();
}
header('Location: ../index.php?page=admin');
exit();
