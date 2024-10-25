<header>
    <div class="header_content w">
        <div class="logo">
            <a href="?page=home"><img src="img/logo/LOGO.png" alt="Логотип"></a>
        </div>
        <div class="nav">
            <div class="burger-icon" id="burger-icon">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
            <nav>
                <a href="?page=about">О нас</a>
                <div class="dropdown">
                    <button onclick="myFunction('dropdown1')" class="dropbtn">Каталог</button>
                    <div id="dropdown1" class="dropdown-content">
                        <a href="?page=complect">Комплекты</a>
                        <a href="?page=acsess">Аксессуары</a>
                        <a href="?page=bags">Сумки</a>
                    </div>
                </div>
                <a href="?page=dostavca">Доставка</a>
                <a href="?page=sale">Акции</a>
                <a href="?page=uslugi">Услуги</a>
            </nav>
            <div class="acc">
                <?php if (isset($_SESSION['user'])):
                    $userId = $_SESSION['user']['id'] ?? null;
                    $userRole = $_SESSION['user']['role'] ?? null;

                    if ($userId && $userRole === 'admin'): ?>
                        <a href="?page=admin&id=<?= htmlspecialchars($userId, ENT_QUOTES, 'UTF-8') ?>">
                            <img src="img/icon/account.png" alt=""></a>
                    <?php elseif ($userId && $userRole === 'user'): ?>
                        <a href="?page=account&id=<?= htmlspecialchars($userId, ENT_QUOTES, 'UTF-8') ?>">
                            <img src="img/icon/account.png" alt=""></a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="?page=sign"><img src="img/icon/account.png" alt=""></a>
                <?php endif; ?>
                <a href="?page=bascet"><img src="img/icon/basket.png" alt="Корзина"></a>
            </div>

        </div>
    </div>
    <div class="menu" id="menu">
        <ul>
            <li><a href="about.php">О нас</a></li>
            <li class="dropdown">
                <button onclick="myFunction('dropdown2')" class="dropbtn">Каталог</button>
                <div id="dropdown2" class="dropdown-content">
                    <a href="?page=complect" style="color: black;">Комплекты</a>
                    <a href="?page=acsess" style="color: black;">Аксессуары</a>
                    <a href="?page=bags" style="color: black;">Сумки</a>
                </div>
            </li>
            <li><a href="?page=dostavca">Доставка</a></li>
            <li><a href="?page=sale">Акции</a></li>
            <li><a href="?page=uslugi">Услуги</a></li>
            <div class="acc">
                <?php if (isset($_SESSION['user'])):
                    $userId = $_SESSION['user']['id'] ?? null;
                    $userRole = $_SESSION['user']['role'] ?? null;

                    if ($userId && $userRole === 'admin'): ?>
                        <a href="?page=admin&id=<?= htmlspecialchars($userId, ENT_QUOTES, 'UTF-8') ?>">
                            <img src="img/icon/account.png" alt=""></a>
                    <?php elseif ($userId && $userRole === 'user'): ?>
                        <a href="?page=account&id=<?= htmlspecialchars($userId, ENT_QUOTES, 'UTF-8') ?>">
                            <img src="img/icon/account.png" alt=""></a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="?page=sign"><img src="img/icon/account.png" alt=""></a>
                <?php endif; ?>
                <a href="?page=bascet"><img src="img/icon/basket.png" alt="Корзина"></a>
            </div>
        </ul>
    </div>
</header>

<div class="blur-overlay" id="blur-overlay"></div>