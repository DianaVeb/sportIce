<?php
session_start();
global $connection;

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
if ($product_id === null) {
    echo "Товар не найден.";
    exit;
}

$sql = "SELECT * FROM products WHERE id = :id";
$query = $connection->prepare($sql);
$query->execute(['id' => $product_id]);
$product = $query->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Товар не найден.";
    exit;
}

$sql = "SELECT * FROM categories";
$categories = $connection->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM `type`";
$types = $connection->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM `generator`";
$generators = $connection->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$typesByCategory = [];
foreach ($types as $type) {
    $typesByCategory[$type['categori_id']][] = $type;
}

$generatorsByCategory = [];
foreach ($generators as $generator) {
    $generatorsByCategory[$generator['categori_id']][] = $generator;
}
?>

<div class="form w py">
    <form action="action/update.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?= $product_id ?>">
        <input type="hidden" name="current_image" value="<?= htmlspecialchars($product['foto']) ?>">

        <div class="form_text">
            <h2>Редактирование товара</h2>
        </div>
        <div class="input_block">
            <h3>Название</h3>
            <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>">
        </div>
        <div class="input_block">
            <h3>Описание</h3>
            <input type="text" name="description" value="<?= htmlspecialchars($product['description']) ?>">
        </div>
        <div class="input_block">
            <h3>Цена</h3>
            <input type="number" name="price" step="0.01" value="<?= htmlspecialchars($product['price']) ?>">
        </div>
        <div class="cat">
            <div class="input_block">
                <h3>Категория</h3>
                <select id="categorySelect" name="category_id" onchange="updateSelects()">
                    <option value="">Выберите категорию</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['id']) ?>" <?= $product['id_categor'] == $category['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input_block">
                <h3>Вид</h3>
                <select id="typeSelect" name="type_id">
                    <option value="">Выберите</option>
                </select>
            </div>
            <div class="input_block">
                <h3>Производитель</h3>
                <select id="generatorSelect" name="generator_id">
                    <option value="">Выберите</option>
                </select>
            </div>
        </div>

        <div class="input_block">
            <h3>Фото</h3>
            <?php if (!empty($product['image_path'])): ?>
                <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="Текущее изображение" style="max-width: 200px; max-height: 200px; display: block; margin-bottom: 10px;">
            <?php endif; ?>
            <input type="file" name="path" accept=".png, .jpg, .jpeg">
            <p style="font-size: 12px; color: red;">Если не выбираете новое изображение, текущее останется без изменений.</p>
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
            <button type="submit">Редактировать</button>
        </div>
    </form>
</div>

<script>
    const typesByCategory = <?= json_encode($typesByCategory) ?>;
    const generatorsByCategory = <?= json_encode($generatorsByCategory) ?>;

    function updateSelects() {
        const categorySelect = document.getElementById("categorySelect");
        const typeSelect = document.getElementById("typeSelect");
        const generatorSelect = document.getElementById("generatorSelect");
        const selectedCategory = categorySelect.value;

        typeSelect.innerHTML = "<option value=''>Выберите</option>";
        generatorSelect.innerHTML = "<option value=''>Выберите</option>";

        if (typesByCategory[selectedCategory]) {
            typesByCategory[selectedCategory].forEach(type => {
                const opt = document.createElement("option");
                opt.value = type.id;
                opt.textContent = type.name;
                typeSelect.appendChild(opt);
            });
        }

        if (generatorsByCategory[selectedCategory]) {
            generatorsByCategory[selectedCategory].forEach(generator => {
                const opt = document.createElement("option");
                opt.value = generator.id;
                opt.textContent = generator.name;
                generatorSelect.appendChild(opt);
            });
        }
    }

    window.onload = function() {
        document.getElementById("categorySelect").value = <?= json_encode($product['id_categor']) ?>;
        updateSelects();

        document.getElementById("typeSelect").value = <?= json_encode($product['id_type']) ?>;
        document.getElementById("generatorSelect").value = <?= json_encode($product['id_generator']) ?>;
    }
</script>
