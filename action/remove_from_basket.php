<?php
session_start();
global $connection;

require_once '../connect/connect.php';

$product_id = $_POST['product_id'];
$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    $query = "DELETE FROM basket WHERE user_id = :user_id AND product_id = :product_id";
    $stmt = $connection->prepare($query);
    $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
} else {
    unset($_SESSION['basket'][$product_id]);
}
header('Location: ../index.php?page=bascet');
exit();
