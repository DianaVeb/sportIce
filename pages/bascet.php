<?php
session_start();
global $connection;

if (!$connection) {
    die('Ошибка подключения к базе данных.');
}

$user_id = $_SESSION['user_id'] ?? null;
$basketItems = [];

if ($user_id) {
    $query = "
        SELECT p.id, p.name, p.price, b.quantity, (p.price * b.quantity) AS total_price
        FROM products p
        JOIN basket b ON p.id = b.product_id
        WHERE b.user_id = :user_id
    ";
    $stmt = $connection->prepare($query);
    $stmt->execute(['user_id' => $user_id]);
    $basketItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    if (isset($_SESSION['basket']) && !empty($_SESSION['basket'])) {
        $productIds = array_keys($_SESSION['basket']);

        $query = "SELECT * FROM products WHERE id IN (" . implode(',', array_fill(0, count($productIds), '?')) . ")";
        $stmt = $connection->prepare($query);
        $stmt->execute($productIds);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as $product) {
            $quantity = $_SESSION['basket'][$product['id']];
            $basketItems[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'total_price' => $product['price'] * $quantity
            ];
        }
    }
}
?>

<div class="bascet w py">
    <div class="bascet_content">
        <h2>Корзина</h2>
        <div class="bascet_tovars">
            <?php foreach ($basketItems as $item): ?>
                <div class="bascet_tovar">
                    <img src="img/tovar/<?= htmlspecialchars($item['name']) ?>.png" alt="">
                    <div class="t_info">
                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                        <p><?= htmlspecialchars($item['price']) ?> ₽</p>

                        <form action="../action/update_quantity.php" method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <input type="hidden" name="change" value="1">
                            <button type="submit">+</button>
                        </form>

                        <input type="text" value="<?= htmlspecialchars($item['quantity']) ?>" readonly>

                        <form action="../action/update_quantity.php" method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <input type="hidden" name="change" value="-1">
                            <button type="submit">-</button>
                        </form>

                        <form action="../action/remove_from_basket.php" method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <button type="submit" class="b">Удалить</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="b_info">
        <h3>Итоговая сумма:</h3>
        <h2><?= array_sum(array_column($basketItems, 'total_price')) ?> ₽</h2>
        <h3><?= count($basketItems) ?> товаров</h3>
        <a href="?page=oformlenie" class="btn">Оформить заказ</a>
    </div>
</div>