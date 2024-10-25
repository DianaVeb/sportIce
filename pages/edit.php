<?php
session_start();
global $connection;

$sql = "SELECT * FROM categories";
$query = $connection->query($sql);
if (!$query) {
    echo "Ошибка запроса: " . $connection->errorInfo()[2];
}
$categories = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM `type`";
$query = $connection->query($sql);
if (!$query) {
    echo "Ошибка запроса: " . $connection->errorInfo()[2];
}
$types = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM `generator`";
$query = $connection->query($sql);
if (!$query) {
    echo "Ошибка запроса: " . $connection->errorInfo()[2];
}
$generators = $query->fetchAll(PDO::FETCH_ASSOC);


$generatorsByCategory = [];
foreach ($generators as $generator) {
    $generatorsByCategory[$generator['categori_id']][] = $generator;
}


$name = $_SESSION['form_data']['name'] ?? '';
$description = $_SESSION['form_data']['description'] ?? '';
$price = $_SESSION['form_data']['price'] ?? '';
$category_id = $_SESSION['form_data']['category_id'] ?? '';
$type_id = $_SESSION['form_data']['type_id'] ?? '';
$generator_id = $_SESSION['form_data']['generator_id'] ?? '';


unset($_SESSION['form_data']);
?>

<div class="form w py">
    <form action="action/edit.php" method="post" enctype="multipart/form-data">
        <div class="form_text">
            <h2>Добавить товар</h2>
        </div>
        <div class="input_block">
            <h3>Название</h3>
            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>">
        </div>
        <div class="input_block">
            <h3>Описание</h3>
            <input type="text" name="description" value="<?= htmlspecialchars($description) ?>">
        </div>
        <div class="input_block">
            <h3>Цена</h3>
            <input type="number" name="price" step="0.01" value="<?= htmlspecialchars($price) ?>">
        </div>
        <div class="cat">
            <div class="input_block">
                <h3>Категория</h3>
                <select id="categorySelect" name="category_id" onchange="updateSelects()">
                    <option value="">Выберите категорию</option>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?= htmlspecialchars($category['id']) ?>" <?= $category['id'] == $category_id ? 'selected' : '' ?>><?= htmlspecialchars($category['name']) ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="input_block">
                <h3>Вид</h3>
                <select id="typeSelect" name="type_id">
                    <option value="">Выберите</option>
                    <?php
                    if (!empty($type_id)) {
                        foreach ($types as $type) {
                            if ($type['id'] == $category_id) { ?>
                                <option value="<?= htmlspecialchars($type['id']) ?>" <?= $type['id'] == $type_id ? 'selected' : '' ?>><?= htmlspecialchars($type['name']) ?></option>
                    <?php }
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="input_block">
                <h3>Производитель</h3>
                <select id="generatorSelect" name="generator_id">
                    <option value="">Выберите</option>
                    <?php
                    $categoryGenerators = $generatorsByCategory[$category_id] ?? [];
                    foreach ($categoryGenerators as $generator) { ?>
                        <option value="<?= htmlspecialchars($generator['id']) ?>" <?= $generator['id'] == $generator_id ? 'selected' : '' ?>><?= htmlspecialchars($generator['name']) ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="input_block">
            <h3>Фото</h3>
            <input type="file" name="path" accept=".png, .jpg, .jpeg">
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
            <button type="submit">Добавить</button>
        </div>
    </form>
</div>

<script>
    const types = <?= json_encode($types) ?>;
    const generatorsByCategory = <?= json_encode($generatorsByCategory) ?>;

    function updateSelects() {
        const categorySelect = document.getElementById("categorySelect");
        const typeSelect = document.getElementById("typeSelect");
        const generatorSelect = document.getElementById("generatorSelect");
        const selectedValue = categorySelect.value;

        typeSelect.innerHTML = "<option value=''>Выберите</option>";
        generatorSelect.innerHTML = "<option value=''>Выберите</option>";

        types.forEach(type => {
            if (type.categori_id == selectedValue) {
                const opt = document.createElement("option");
                opt.value = type.id;
                opt.innerHTML = type.name;
                typeSelect.appendChild(opt);
            }
        });

        if (selectedValue in generatorsByCategory) {
            generatorsByCategory[selectedValue].forEach(generator => {
                const opt = document.createElement("option");
                opt.value = generator.id;
                opt.innerHTML = generator.name;
                generatorSelect.appendChild(opt);
            });
        }

    }

    window.onload = function() {
        document.getElementById("categorySelect").value = <?= json_encode($category_id) ?>;
        updateSelects();
        document.getElementById("typeSelect").value = <?= json_encode($type_id) ?>;
        document.getElementById("generatorSelect").value = <?= json_encode($generator_id) ?>;
    }
</script>