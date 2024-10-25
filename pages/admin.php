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
            <h3>Добро пожаловать, Анна!</h3>
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

<div class="zacaz w py" id="zacaz">
    <h2>Заказы</h2>
    <div class="tovars">
        <div class="tovar">
            <img src="img/tovar/komplect.png" alt="">
            <h3>Термокомплект “Сигма”</h3>
            <div class="tovar_info">
                <p>5 900 ₽</p>
                <button id="open-modal">Подробнее</button>
                <div id="modal">
                    <div id="modal-content">
                        <h2>Подробнее о заказе</h2>
                        <div class="filt">
                            <h3>Название товара:</h3>
                            <p>Термокомплект “Сигма” 1 шт.</p>
                        </div>
                        <div class="filt">
                            <h3>ФИО покупателя:</h3>
                            <p>Иванов Иван Иванович</p>
                        </div>
                        <div class="filt">
                            <h3>Дата:</h3>
                            <p>09.10.2024</p>
                        </div>
                        <div class="filt">
                            <h3>Способ получения:</h3>
                            <p>Доставка</p>
                        </div>
                        <div class="filt">
                            <h3>Адрес:</h3>
                            <p>лавомтлавоми</p>
                        </div>
                        <div class="filt">
                            <h3>Сумма:</h3>
                            <p>1 500 ₽</p>
                        </div>
                        <button id="close-modal">X</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="work_tovar w py" style="display: none;" id="work_tovar">
    <div class="admin_opis">
        <h2>Работа с товарами</h2>
        <div class="edit_tovar">
            <a href="?page=edit" class="b">Добавить товар</a>
        </div>
    </div>
    <div class="tovars">
        <?php foreach ($products as $product): ?>
            <div class="tovar">
                <img src="<?= htmlspecialchars($product['foto']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <div class="tovar_info">
                    <a href="?page=update&id=<?= htmlspecialchars($product['id']) ?>" class="b">Изменить</a>
                    <form action="action/delete_product.php" method="post" style="display: inline;">
                        <input type="hidden" name="deleteProduct" value="<?= htmlspecialchars($product['id']) ?>">
                        <a href="?page=confirm_delete&id=<?= htmlspecialchars($product['id']) ?>" class="b">Удалить</a>

                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>