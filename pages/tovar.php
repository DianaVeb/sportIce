<?php
session_start();
global $connection;

if (!$connection) {
    die('Ошибка подключения к базе данных.');
}

$product_id = $_GET['id'] ?? null;
if (!$product_id) {
    header('Location: ../index.php?page=home');
    exit();
}

$query = "SELECT * FROM products WHERE id = :id";
$stmt = $connection->prepare($query);
$stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
$stmt->execute();

$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    header('Location: ../index.php?page=home');
    exit();
}

$categoryQuery = "SELECT name FROM categories WHERE id = :id_categor";
$categoryStmt = $connection->prepare($categoryQuery);
$categoryStmt->bindParam(':id_categor', $product['id_categor'], PDO::PARAM_INT);
$categoryStmt->execute();

$category = $categoryStmt->fetch(PDO::FETCH_ASSOC);
$category_name = $category['name'] ?? 'Категория не найдена';


$generatorQuery = "SELECT name FROM generator WHERE id = :id_categor";
$generatorStmt = $connection->prepare($generatorQuery);
$generatorStmt->bindParam(':id_categor', $product['id_categor'], PDO::PARAM_INT);
$generatorStmt->execute();

$generator = $generatorStmt->fetch(PDO::FETCH_ASSOC);
$generator_name = $generator['name'] ?? 'Производитель не найден';

?>

<div class="bread w py">
    <a href="index.php">Главная -> </a>
    <a href="/?page=bags&id_categor=<?= htmlspecialchars($product['id_categor']) ?>">
        <?= htmlspecialchars($category_name) ?> ->
    </a>
    <h3><?= htmlspecialchars($product['name']) ?></h3>
</div>

<div class="tovar_about w py">
    <div class="t_img">
        <img src="<?= htmlspecialchars($product['foto']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
    </div>
    <div class="t_a_info">
        <h2><?= htmlspecialchars($product['name']) ?></h2>
        <p><?= htmlspecialchars($product['description']) ?></p>
        <div class="i">
            <h1><?= htmlspecialchars($product['price']) ?>₽</h1>
            <form action="../action/add_to_basket.php" method="POST">
                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                <button type="submit" class="b">В корзину</button>
            </form>
        </div>
        <p>Производитель: <?= htmlspecialchars($generator_name) ?></p>
    </div>
</div>