<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sport Ice</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Kurale&display=swap" rel="stylesheet">
    <script src="js/index.js" defer></script>
</head>

<body>
    <header>
        <div class="header_content w">
            <div class="logo">
                <a href="index.html"><img src="img/logo/LOGO.png" alt="Логотип"></a>
            </div>
            <div class="nav">
                <div class="burger-icon" id="burger-icon">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
                <nav>
                    <a href="about.html">О нас</a>
                    <div class="dropdown">
                        <button onclick="myFunction('dropdown1')" class="dropbtn">Каталог</button>
                        <div id="dropdown1" class="dropdown-content">
                            <a href="complect.html">Комплекты</a>
                            <a href="acsess.html">Аксессуары</a>
                            <a href="bags.html">Сумки</a>
                        </div>
                    </div>
                    <a href="dostavca.html">Доставка</a>
                    <a href="sale.html">Акции</a>
                    <a href="uslugi.html">Услуги</a>
                </nav>
                <div class="acc">
                    <a href="sign.html"><img src="img/icon/account.png" alt="Аккаунт"></a>
                    <a href="bascet.html"><img src="img/icon/basket.png" alt="Корзина"></a>
                </div>
            </div>
        </div>
        <div class="menu" id="menu">
            <ul>
                <li><a href="about.html">О нас</a></li>
                <li class="dropdown">
                    <button onclick="myFunction('dropdown2')" class="dropbtn">Каталог</button>
                    <div id="dropdown2" class="dropdown-content">
                        <a href="complect.html" style="color: black;">Комплекты</a>
                        <a href="acsess.html" style="color: black;">Аксессуары</a>
                        <a href="bags.html" style="color: black;">Сумки</a>
                    </div>
                </li>
                <li><a href="dostavca.html">Доставка</a></li>
                <li><a href="sale.html">Акции</a></li>
                <li><a href="uslugi.html">Услуги</a></li>
                <div class="acc">
                    <a href="sign.html"><img src="img/icon/account.png" alt="Аккаунт"></a>
                    <a href="bascet.html"><img src="img/icon/basket.png" alt="Корзина"></a>
                </div>
            </ul>
        </div>
    </header>

    <div class="blur-overlay" id="blur-overlay"></div>
    <div class="form w py">
        <form>
            <div class="form_text">
                <h2>Редактирование товара</h2>
            </div>
            <div class="input_block">
                <h3>Название</h3>
                <input type="text">
            </div>
            <div class="input_block">
                <h3>Описание</h3>
                <input type="text">
            </div>
            <div class="input_block">
                <h3>Цена</h3>
                <input type="text">
            </div>
            <div class="cat">
                <div class="input_block">
                    <h3>Категория</h3>
                    <select>
                        <option>Сумки</option>
                        <option>Аксессуары</option>
                        <option>Костюмы</option>
                    </select>
                </div>
                <div class="input_block">
                    <h3>Вид</h3>
                    <select>
                        <option>Косметички</option>
                        <option>На колесиках</option>
                        <option>На пояс</option>
                        <option>Мешки для обуви</option>
                        <option>Портпледы</option>
                        <option>Рюкзаки</option>
                    </select>
                </div>
                <div class="input_block">
                    <h3>Производитель</h3>
                    <select>
                        <option>Голограмма</option>
                        <option>Флип</option>
                        <option>Бильман</option>
                        <option>RUNA</option>
                        <option>MIX</option>
                    </select>
                </div>
            </div>
            <div class="input_block">
                <h3>Фото</h3>
                <input type="file">
            </div>
            <div class="form_btn">
                <button>Редактировать</button>
            </div>
        </form>
    </div>
    <footer>
        <div class="footer_content w">
            <div class="f1">
                <img src="img/logo/LOGO.png" alt="">
                <div class="info_content">
                    <div class="info">
                        <a href="uslugi.html #zatochca">Заточка коньков</a>
                        <a href="dostavca.html">Доставка</a>
                        <a href="uslugi.html #tremovca">Термоформовка коньков</a>
                    </div>
                    <div class="info">
                        <a href="complect.html">Комплекты</a>
                        <a href="acsess.html">Аксессуары</a>
                        <a href="bags.html">Сумки</a>
                    </div>
                    <div class="info">
                        <a href="about.html">О компании</a>
                        <a href="sale.html">Акции</a>
                        <a href="contact.html">Контакты</a>
                    </div>
                </div>
                <div class="cont">
                    <a href="tel:+78008008000">8(800)800-80-00</a>
                    <img src="img/icon/seti.png" alt="">
                    <p>Г. Казань, ул. Чистопольская, 7 </p>
                </div>
            </div>
            <div class="f2">
                <h3>©Исхакова Диана, 2024</h3>
            </div>
        </div>
    </footer>
</body>

</html>