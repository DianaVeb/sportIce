<div class="form w py">
    <form action="action/sign.php" method="post">
        <div class="form_text">
            <h2>Вход</h2>
            <a href="?page=register">/ Регистрация</a>
        </div>
        <div class="input_block">
            <h3>Телефон</h3>
            <input type="text" name="tel" value="<?php
            if (isset($_SESSION['form_data']['tel'])) {
                echo $_SESSION['form_data']['tel'];
                unset($_SESSION['form_data']['tel']);
            }
            ?>">
        </div>
        <div class="input_block">
            <h3>Пароль</h3>
            <input type="password" name="password">
        </div>
        <?php
        if (isset($_SESSION['errors'])) {
            foreach ($_SESSION['errors'] as $error) {
                echo "<p style='color: red;'>$error</p>";
            }
            unset($_SESSION['errors']);
        }
        ?>
        <div class="form_btn">
            <button type="submit">Войти</button>
        </div>
    </form>
</div>