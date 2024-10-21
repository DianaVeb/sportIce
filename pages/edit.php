<?php
$sql = "SELECT * FROM categories";
$query = $connection->query($sql);
$categories = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM `type`";
$query = $connection->query($sql);
$types = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM `generator`";
$query = $connection->query($sql);
$generators = $query->fetchAll(PDO::FETCH_ASSOC);

// Prepare generators by category
$generatorsByCategory = [];
foreach ($categories as $category) {
    $generatorsByCategory[$category['id']] = array_filter($generators, function ($generator) use ($category) {
        return $generator['categori_id'] == $category['id'];
    });
}
?>
<div class="form w py" action="action/edit.php" method="post" enctype="multipart/form-data">
    <form>
        <div class="form_text">
            <h2>Добавить товар</h2>
        </div>
        <div class="input_block">
            <h3>Название</h3>
            <input type="text" name="name">
        </div>
        <div class="input_block">
            <h3>Описание</h3>
            <input type="text" name="description">
        </div>
        <div class="input_block">
            <h3>Цена</h3>
            <input type="text" name="price">
        </div>
        <div class="cat">
            <div class="input_block">
                <h3>Категория</h3>
                <select id="categorySelect" name="category_id" onchange="updateSelects()">
                    <option value="">Выберите категорию</option>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="input_block">
                <h3>Вид</h3>
                <select id="typeSelect" name="type_id">
                    <option value="">Выберите</option>
                    <!-- Опции для вида будут динамически добавлены здесь -->
                </select>
            </div>
            <div class="input_block">
                <h3>Производитель</h3>
                <select id="generatorSelect" name="generator_id">
                    <option value="">Выберите</option>
                    <!-- Опции для производителя будут динамически добавлены здесь -->
                </select>
            </div>
        </div>

        <script>
            var typesByCategory = {
                <?php foreach ($categories as $category) { ?> "<?= $category['id'] ?>": [
                        <?php
                        $categoryTypes = array_filter($types, function ($type) use ($category) {
                            return $type['categori_id'] == $category['id'];
                        });
                        foreach ($categoryTypes as $key => $type) { ?> {
                                id: "<?= $type['id'] ?>",
                                name: "<?= $type['name'] ?>"
                            }
                            <?php if ($key < count($categoryTypes) - 1) echo ','; ?>
                        <?php } ?>
                    ] <?php if ($category !== end($categories)) echo ','; ?>
                <?php } ?>
            };      

            var generatorsByCategory = {
                <?php foreach ($generatorsByCategory as $catId => $genList) { ?> "<?= $catId ?>": [
                        <?php foreach ($genList as $key => $generator) { ?> {
                                id: "<?= $generator['id'] ?>",
                                name: "<?= $generator['name'] ?>"
                            }
                            <?php if ($key < count($genList) - 1) echo ','; ?>
                        <?php } ?>
                    ] <?php if ($catId !== array_key_last($generatorsByCategory)) echo ','; ?>
                <?php } ?>
            };


            function updateSelects() {
                var categorySelect = document.getElementById("categorySelect");
                var typeSelect = document.getElementById("typeSelect");
                var generatorSelect = document.getElementById("generatorSelect");
                var selectedValue = categorySelect.value;

                // Очищаем текущие опции второго select
                typeSelect.innerHTML = "<option value=''>Выберите</option>";
                generatorSelect.innerHTML = "<option value=''>Выберите</option>";

                if (selectedValue in typesByCategory) {
                    var types = typesByCategory[selectedValue];
                    types.forEach(function(type) {
                        var opt = document.createElement("option");
                        opt.value = type.id;
                        opt.innerHTML = type.name;
                        typeSelect.appendChild(opt);
                    });
                }

                if (selectedValue in generatorsByCategory) {
                    var generators = generatorsByCategory[selectedValue];
                    generators.forEach(function(generator) {
                        var opt = document.createElement("option");
                        opt.value = generator.id;
                        opt.innerHTML = generator.name;
                        generatorSelect.appendChild(opt);
                    });
                }
            }
        </script>
        <div class="input_block">
            <h3>Фото</h3>
            <input type="file" name="path">
        </div>
        <div class="form_btn">
            <button>Добавить</button>
        </div>
    </form>
</div>