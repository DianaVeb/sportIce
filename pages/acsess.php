<?php
session_start();
global $connection;

if (!$connection) {
    die('Ошибка подключения к базе данных.');
}

$id_categor = 2;
$searchQuery = $_GET['search'] ?? '';
$id_generator = $_GET['generator'] ?? '';
$id_type = $_GET['type'] ?? '';
$page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

$query = "SELECT * FROM products WHERE id_categor = :id_categor";
$params = [':id_categor' => $id_categor];

if (!empty($searchQuery)) {
    $query .= " AND name LIKE :search";
    $params[':search'] = '%' . $searchQuery . '%';
}

if (!empty($id_generator)) {
    $query .= " AND id_generator = :id_generator";
    $params[':id_generator'] = $id_generator;
}

if (!empty($id_type)) {
    $query .= " AND id_type = :id_type";
    $params[':id_type'] = $id_type;
}

$countQuery = str_replace("SELECT *", "SELECT COUNT(*) as total", $query);
$stmt = $connection->prepare($countQuery);
$stmt->execute($params);
$totalProducts = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalProducts / $limit);

$query .= " LIMIT :limit OFFSET :offset";
$params[':limit'] = $limit;
$params[':offset'] = $offset;

$stmt = $connection->prepare($query);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$queryGenerators = "SELECT * FROM generator WHERE id = :id_categor";
$stmtGenerators = $connection->prepare($queryGenerators);
$stmtGenerators->execute([':id_categor' => $id_categor]);
$generators = $stmtGenerators->fetchAll(PDO::FETCH_ASSOC);

$queryTypes = "SELECT * FROM type WHERE id = :id_categor";
$stmtTypes = $connection->prepare($queryTypes);
$stmtTypes->execute([':id_categor' => $id_categor]);
$types = $stmtTypes->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="ac_bag">
    <div class="b_b_content w">
        <h1>Аксессуары</h1>
    </div>
</div>


<div class="bb w py">
    <div class="bb_h2">
        <h2>Аксессуары</h2>
    </div>
    <div class="bb_filt">
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="get">
            <input type="hidden" name="page" value="complect" />
            <input type="text" id="search" name="search" placeholder="Введите название товара" value="<?= htmlspecialchars($searchQuery) ?>" />
            <button class="b">Поиск</button>
        </form>

        <button id="open-modal">Фильтр</button>
        <div id="modal">
            <div id="modal-content">
                <h2>Фильтр</h2>
                <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="get">
                    <input type="hidden" name="page" value="complect" />

                    <div class="filts">
                        <div class="filt">
                            <h3>Производитель:</h3>
                            <div class="fil">
                                <input type="radio" name="generator" value="" <?= $id_generator === '' ? 'checked' : '' ?>> Все<br>
                            </div>
                            <?php foreach ($generators as $generator): ?>
                                <div class="fil">
                                    <input type="radio" name="generator" value="<?= htmlspecialchars($generator['id']) ?>" <?= $id_generator === (string)$generator['id'] ? 'checked' : '' ?>>
                                    <?= htmlspecialchars($generator['name']) ?><br>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="filt">
                            <h3>Вид:</h3>
                            <div class="fil">
                                <input type="radio" name="type" value="" <?= $id_type === '' ? 'checked' : '' ?>> Все<br>
                            </div>
                            <?php foreach ($types as $type): ?>
                                <div class="fil">
                                    <input type="radio" name="type" value="<?= htmlspecialchars($type['id']) ?>" <?= $id_type === (string)$type['id'] ? 'checked' : '' ?>>
                                    <?= htmlspecialchars($type['name']) ?><br>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="f_button">
                        <button type="submit" class="b">Применить</button>
                        <button type="button" class="b" id="reset-filters">Сбросить</button>
                    </div>
                </form>
                <button id="close-modal">X</button>
            </div>
        </div>
    </div>

    <div class="tovars">
        <?php foreach ($products as $product): ?>
            <div class="tovar">
                <a href="/?page=tovar&id=<?= htmlspecialchars($product['id']) ?>">
                    <img src="<?= htmlspecialchars($product['foto']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                </a>
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <div class="tovar_info">
                    <p><?= htmlspecialchars($product['price']) ?>₽</p>
                    <button class="b">В корзину</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=acsess&page_num=<?= $page - 1; ?>" class="prev">Назад</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=acsess&page_num=<?= $i; ?>" class="<?= $i === $page ? 'active' : ''; ?>">
                <?= $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=acsess&page_num=<?= $page + 1; ?>" class="next">Вперед</a>
        <?php endif; ?>
    </div>

</div>

<script>
    document.getElementById('open-modal').onclick = function() {
        document.getElementById('modal').style.display = 'block';
    };

    document.getElementById('close-modal').onclick = function() {
        document.getElementById('modal').style.display = 'none';
    };

    window.onclick = function(event) {
        if (event.target === document.getElementById('modal')) {
            document.getElementById('modal').classList.remove('active');
        }
    };

    document.getElementById('reset-filters').onclick = function() {
        document.querySelector('input[name="generator"][value=""]').checked = true;
        document.querySelector('input[name="type"][value=""]').checked = true;
    };
</script>