<?php
session_start();
global $connection;

require_once '../connect/connect.php';

$product_id = $_POST['product_id'];
$change = (int)$_POST['change'];
$user_id = $_SESSION['user']['id'] ?? null;

if ($user_id) {
    $query = "UPDATE basket SET quantity = quantity + :change WHERE user_id = :user_id AND product_id = :product_id";
    $stmt = $connection->prepare($query);
    $stmt->execute(['change' => $change, 'user_id' => $user_id, 'product_id' => $product_id]);
} else {
    $_SESSION['basket'][$product_id] += $change;
    if ($_SESSION['basket'][$product_id] <= 0) {
        unset($_SESSION['basket'][$product_id]);
    }
}
header('Location: ../index.php?page=bascet');
exit();
