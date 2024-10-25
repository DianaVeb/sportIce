<?php
session_start();
global $connection;

require_once '../connect/connect.php';

if (!$connection) {
    die('Ошибка подключения к базе данных.');
}

$product_id = $_POST['product_id'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;
$quantity = $_POST['quantity'] ?? 1;

if (!$product_id) {
    header('Location: ../index.php?page=home');
    exit();
}

if ($user_id) {
    $query = "SELECT * FROM basket WHERE user_id = :user_id AND product_id = :product_id";
    $stmt = $connection->prepare($query);
    $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
    $basketItem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($basketItem) {
        $updateQuery = "UPDATE basket SET quantity = quantity + :quantity WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $connection->prepare($updateQuery);
        $stmt->execute(['quantity' => $quantity, 'user_id' => $user_id, 'product_id' => $product_id]);
    } else {
        $insertQuery = "INSERT INTO basket (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)";
        $stmt = $connection->prepare($insertQuery);
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id, 'quantity' => $quantity]);
    }
} else {
    $_SESSION['basket'][$product_id] = ($_SESSION['basket'][$product_id] ?? 0) + $quantity;
}

header('Location: ../index.php?page=bascet');
exit();
