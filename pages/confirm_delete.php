<?php
session_start();
global $connection;

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php?page=home');
    exit();
}

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $connection->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Продукт не найден.";
    exit();
}
?>

<h2>Вы уверены, что хотите удалить продукт "<?= htmlspecialchars($product['name']) ?>"?</h2>
<form action="../action/delete_product.php" method="post">
    <input type="hidden" name="id" value="<?= htmlspecialchars($productId) ?>">
    <button type="submit" name="confirm" value="yes">Да, удалить</button>
    <a href="?page=admin" class="b">Отмена</a>
</form>