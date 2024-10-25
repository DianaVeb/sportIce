<?php
session_start();
global $connection;

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header('Location: ../index.php?page=home');
    exit();
}

$userId = $_SESSION['user']['id'];

$sql = "SELECT * FROM user WHERE id = :id";
$query = $connection->prepare($sql);
$query->execute(['id' => $userId]);
$user = $query->fetch(PDO::FETCH_ASSOC);

?>
<div class="account w py">
    <h2>Мой кабинет</h2>
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

<div class="zacaz w py">
    <h2>Мои заказы</h2>
    <div class="tovars">
        <div class="tovar">
            <img src="img/tovar/komplect.png" alt="">
            <h3>Термокомплект “Сигма”</h3>
            <div class="tovar_info">
                <p>5 900 ₽</p>
                <button class="b">Повторить</button>
            </div>
        </div>
    </div>
</div>