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
    foreach ($categories as $category) {
        $generatorsByCategory[$category['id']] = array_filter($generators, function ($generator) use ($category) {
            return $generator['categori_id'] == $category['id'];
        });
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
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?= htmlspecialchars($category['id']) ?>" <?= $product['id_categor'] == $category['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="input_block">
                <h3>Вид</h3>
                <select id="typeSelect" name="type_id">
                    <option value="">Выберите</option>
                    <?php foreach ($types as $type) {
                        if ($type['id_categor'] == $product['id_categor']) { ?>
                            <option value="<?= htmlspecialchars($type['id']) ?>" <?= $product['id_type'] == $type['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($type['name']) ?>
                            </option>
                    <?php }
                    } ?>
                </select>
            </div>
            <div class="input_block">
                <h3>Производитель</h3>
                <select id="generatorSelect" name="generator_id">
                    <option value="">Выберите</option>
                    <?php foreach ($generators as $generator) {
                        if ($generator['id_categor'] == $product['id_categor']) { ?>
                            <option value="<?= htmlspecialchars($generator['id']) ?>" <?= $product['id_generator'] == $generator['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($generator['name']) ?>
                            </option>
                    <?php }
                    } ?>
                </select>
            </div>
        </div>

        <div class="input_block">
            <h3>Фото</h3>
            <?php if (!empty($product['image_path'])):
            ?>
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
    const typesByCategory = <?= json_encode($types) ?>;
    const generatorsByCategory = <?= json_encode($generatorsByCategory) ?>;

    function updateSelects() {
        const categorySelect = document.getElementById("categorySelect");
        const typeSelect = document.getElementById("typeSelect");
        const generatorSelect = document.getElementById("generatorSelect");
        const selectedValue = categorySelect.value;

        typeSelect.innerHTML = "<option value=''>Выберите</option>";
        generatorSelect.innerHTML = "<option value=''>Выберите</option>";

        typesByCategory.forEach(type => {
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
        const categorySelect = document.getElementById("categorySelect");
        categorySelect.value = <?= json_encode($product['id_categor']) ?>;
        updateSelects();

        document.getElementById("typeSelect").value = <?= json_encode($product['id_type']) ?>;
        document.getElementById("generatorSelect").value = <?= json_encode($product['id_generator']) ?>;
    }
</script>