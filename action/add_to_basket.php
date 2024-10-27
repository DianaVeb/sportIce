<?php
session_start();
global $connection;
require_once '../connect/connect.php';
if (!$connection) {
    die('Ошибка подключения к базе данных.');
}

$product_id = $_POST['product_id'] ?? null;
$user_id = $_SESSION['user']['id'] ?? null;
$quantity = $_POST['quantity'] ?? 1;

if (!$product_id) {
    header('Location: ../index.php?page=home');
    exit();
}

try {
    if ($user_id) {
        // Начинаем транзакцию для надежности выполнения
        $connection->beginTransaction();

        // Проверяем, существует ли товар уже в корзине пользователя
        $query = "SELECT quantity FROM basket WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $connection->prepare($query);
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
        $basketItem = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($basketItem) {
            // Обновляем количество, если товар уже в корзине
            $updateQuery = "UPDATE basket SET quantity = quantity + :quantity WHERE user_id = :user_id AND product_id = :product_id";
            $stmt = $connection->prepare($updateQuery);
            $stmt->execute([
                'quantity' => $quantity,
                'user_id' => $user_id,
                'product_id' => $product_id
            ]);
        } else {
            // Вставляем новую запись, если товара нет в корзине
            $insertQuery = "INSERT INTO basket (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)";
            $stmt = $connection->prepare($insertQuery);
            $stmt->execute([
                'user_id' => $user_id,
                'product_id' => $product_id,
                'quantity' => $quantity
            ]);
        }

        // Подтверждаем транзакцию
        $connection->commit();
    } else {
        // Для гостей сохраняем корзину в сессии
        $_SESSION['basket'][$product_id] = ($_SESSION['basket'][$product_id] ?? 0) + $quantity;
    }

    // Перенаправляем на страницу корзины
    header('Location: ../index.php?page=bascet');
    exit();

} catch (Exception $e) {
    // Откат транзакции в случае ошибки
    $connection->rollBack();
    die('Ошибка при добавлении товара в корзину: ' . $e->getMessage());
}
?>
