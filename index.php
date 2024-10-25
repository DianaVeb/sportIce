<?php
session_start();

// Подключение к базе данных
$connection = require_once "connect/connect.php";
if (!$connection) {
    die('Ошибка подключения к базе данных.');
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
        'confirm_delete'     => 'pages/confirm_delete.php',

    ];

    $page = $_GET['page'] ?? 'home';

    if (array_key_exists($page, $routes)) {
        include $routes[$page];
    } else {
        include 'pages/not.php';
    }

    include("incl/footer.php");
    ?>
</body>

</html>
