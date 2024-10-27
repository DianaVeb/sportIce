<?php
session_start();
global $connection;

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php?page=home');
    exit();
}

$sql = "SELECT * FROM products";
$query = $connection->query($sql);
$products = $query->fetchAll(PDO::FETCH_ASSOC);


$userId = $_SESSION['user']['id'];

$sql = "SELECT * FROM user WHERE id = :id";
$query = $connection->prepare($sql);
$query->execute(['id' => $userId]);
$user = $query->fetch(PDO::FETCH_ASSOC);


$orderSql = "
    SELECT po.id AS order_id, po.full_name, po.address, po.tel, po.created_at, 
           p.id AS product_id, p.name AS product_name, p.price, b.quantity
    FROM placing_order po
    JOIN basket b ON po.basket_id = b.id
    JOIN products p ON b.product_id = p.id
    ORDER BY po.created_at DESC
";
$orderQuery = $connection->prepare($orderSql);
$orderQuery->execute();
$orders = $orderQuery->fetchAll(PDO::FETCH_ASSOC);
?>



<div class="account w py">
    <h2>Панель администратора</h2>
    <div class="account_content py">
        <div class="account_form">
            <div class="label">
                <h3>Имя</h3>
                <h3>Фамилия</h3>
                <h3>Отчество</h3>
                <h3>Телефон</h3>
                <h3>Старый пароль</h3>
                <h3>Новый пароль</h3>
            </div>
            <form action="action/update_user.php" method="post">
                <input type="text" name="name" placeholder="Имя" value="<?= htmlspecialchars($user['name'] ?? '') ?>">
                <input type="text" name="surname" placeholder="Фамилия" value="<?= htmlspecialchars($user['surname'] ?? '') ?>">
                <input type="text" name="pathname" placeholder="Отчество" value="<?= htmlspecialchars($user['pathname'] ?? '') ?>">
                <input type="text" disabled style="border: 2px solid gray;" placeholder="Телефон" value="<?= htmlspecialchars($user['tel'] ?? '') ?>">
                <input type="password" name="old_password" placeholder="Старый пароль">
                <input type="password" name="new_password" placeholder="Новый пароль">
                <?php
                if (isset($_SESSION['errors'])) {
                    foreach ($_SESSION['errors'] as $error) {
                        echo "<p style='color: red;'>$error</p>";
                    }
                    unset($_SESSION['errors']);
                }
                ?>
                <button type="submit" class="b">Изменить</button>
            </form>
        </div>
        <div class="acc_foto">
            <img src="img/account/acc.png" alt="">
            <h3>Добро пожаловать, <?= htmlspecialchars($user['name'] ?? '') ?>!</h3>
            <form action="../action/logout.php" method="post" style="display: inline;">
                <button type="submit" class="b">Выйти</button>
            </form>
        </div>
    </div>
</div>

<div class="perecl w">
    <button class="bot" id="zacazs">Заказы</button>
    <button class="b" id="tovars">Работа с товарами</button>
</div>

<div class="zacaz w py">
    <h2>Заказы</h2>
    <div class="tovars">
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <div class="tovar">
                    <h3>Заказ №<?= htmlspecialchars($order['order_id']) ?></h3>
                    <p>Дата: <?= htmlspecialchars($order['created_at']) ?></p>
                    <h4>Товар: <?= htmlspecialchars($order['product_name']) ?></h4>
                    <p>Цена: <?= htmlspecialchars($order['price']) ?> ₽</p>
                    <p>Количество: <?= htmlspecialchars($order['quantity']) ?></p>
                    <p>Получатель: <?= htmlspecialchars($order['full_name']) ?></p>
                    <?php if (!empty($order['address'])): ?>
                        <p>Адрес доставки: <?= htmlspecialchars($order['address']) ?></p>
                    <?php endif; ?>
                    <button class="b">Повторить</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>У вас пока нет заказов.</p>
        <?php endif; ?>
    </div>
</div>