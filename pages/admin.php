<?
global $connection;
if(isset($_GET['deleteProduct'])){
    $sql = "DELETE FROM products WHERE `products`.`id` = {$_GET['deleteProduct']}";
    $connection->query($sql);
    header('Location: ../index.php?page=admin');
}
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
            <form action="">
                <input type="text">
                <input type="text">
                <input type="text">
                <input type="text" disabled style="border: 2px solid gray;">
                <input type="password">
                <input type="password">
                <button class="b">Изменить</button>
            </form>
        </div>
        <div class="acc_foto">
            <img src="img/account/acc.png" alt="">
            <h3>Добро пожаловать, Анна!</h3>
            <button class="b">Выйти</button>
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
        <div class="tovar">
            <img src="img/tovar/komplect.png" alt="">
            <h3>Термокомплект “Сигма”</h3>
            <div class="tovar_info">
                <a href="update.html" class="b">Изменить</a>
                <a href="?page=admin&deleteProduct=<?=$products['id']?>" class="b">Удалить</a>
            </div>
        </div>
    </div>
</div>