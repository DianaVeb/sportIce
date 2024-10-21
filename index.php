<?php
session_start();

// Подключение к базе данных
$connection = require_once "connect/connect.php";
if (!$connection) {
    die('Ошибка подключения к базе данных.'); // Проверка подключения
}
?>
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
    <?php
    include("incl/header.php");

    // Массив маршрутов и соответствующих файлов
    $routes = [
        'tovar'      => 'pages/tovar.php',
        'edit'       => 'pages/edit.php',
        'acsess'     => 'pages/acsess.php',
        'bags'       => 'pages/bags.php',
        'bascet'     => 'pages/bascet.php',
        'complect'   => 'pages/complect.php',
        'contact'    => 'pages/contact.php',
        'oformlenie' => 'pages/oformlenie.php',
        'dostavca'   => 'pages/dostavca.php',
        'sale'       => 'pages/sale.php',
        'uslugi'     => 'pages/uslugi.php',
        'home'       => 'pages/home.php',
        'about'      => 'pages/about.php',
        'account'    => 'pages/account.php',
        'admin'      => 'pages/admin.php',
        'sign'       => 'pages/sign.php',
        'register'   => 'pages/register.php',
        'update'     => 'pages/update.php',
    ];

    // Определение страницы по параметру 'page'
    $page = $_GET['page'] ?? 'home'; // По умолчанию - 'home'

    // Подключаем нужный файл или страницу 404
    if (array_key_exists($page, $routes)) {
        include $routes[$page];
    } else {
        include 'pages/not.php'; // Страница ошибки (404)
    }

    include("incl/footer.php");
    ?>
</body>

</html>
