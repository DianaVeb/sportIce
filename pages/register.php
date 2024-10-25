<div class="form w py">
    <form action="action/register.php" method="post">
        <div class="form_text">
            <h2>Регистрация</h2>
            <a href="index.php?page=sign">/ Вход</a>
        </div>
        <div class="input_block">
            <h3>Фамилия *</h3>
            <input type="text" name="surname"
                value="<?php
                        if (isset($_SESSION['form_data']['surname'])) {
                            echo $_SESSION['form_data']['surname'];
                            unset($_SESSION['form_data']['surname']);
                        }
                        ?>">
        </div>
        <div class="input_block">
            <h3>Имя *</h3>
            <input type="text" name="name"
                value="<?php
                        if (isset($_SESSION['form_data']['name'])) {
                            echo $_SESSION['form_data']['name'];
                            unset($_SESSION['form_data']['name']);
                        }
                        ?>">
        </div>
        <div class="input_block">
            <h3>Отчество</h3>
            <input type="text" name="pathname"
                value="<?php
                        if (isset($_SESSION['form_data']['pathname'])) {
                            echo $_SESSION['form_data']['pathname'];
                            unset($_SESSION['form_data']['pathname']);
                        }
                        ?>">
        </div>
        <div class="input_block">
            <h3>Телефон *</h3>
            <input type="text" name="tel"
                value="<?php
                        if (isset($_SESSION['form_data']['tel'])) {
                            echo $_SESSION['form_data']['tel'];
                            unset($_SESSION['form_data']['tel']);
                        }
                        ?>">
        </div>
        <div class="input_block">
            <h3>Пароль *</h3>
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
            <button type="submit">Зарегистрироваться</button>
        </div>
    </form>
    <a href="index.php?page=account">аккаунт</a>
</div>