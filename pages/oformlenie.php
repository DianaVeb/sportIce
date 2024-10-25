<?php
session_start();
global $connection;

if (!$connection) {
    die('Ошибка подключения к базе данных.');
}

$user_id = $_SESSION['user_id'] ?? null;
$basketItems = [];
$totalAmount = 0;

if ($user_id) {
    $query = "
        SELECT p.id, p.name, p.price, b.quantity, (p.price * b.quantity) AS total_price
        FROM products p
        JOIN basket b ON p.id = b.product_id
        WHERE b.user_id = :user_id
    ";
    $stmt = $connection->prepare($query);
    $stmt->execute(['user_id' => $user_id]);
    $basketItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    if (isset($_SESSION['basket']) && !empty($_SESSION['basket'])) {
        $productIds = array_keys($_SESSION['basket']);

        $query = "SELECT * FROM products WHERE id IN (" . implode(',', array_fill(0, count($productIds), '?')) . ")";
        $stmt = $connection->prepare($query);
        $stmt->execute($productIds);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as $product) {
            $quantity = $_SESSION['basket'][$product['id']];
            $basketItems[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'total_price' => $product['price'] * $quantity
            ];
        }
    }
}

$totalAmount = array_sum(array_column($basketItems, 'total_price'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullname = $_POST['fullname'];
    $deliveryMethod = $_POST['delivery_method'];
    $address = $deliveryMethod === 'delivery' ? $_POST['address'] : '';

    // Здесь код для сохранения заказа в базу данных
    // Примерно так:
    // $insertQuery = "INSERT INTO orders (user_id, fullname, delivery_method, address, total_amount) VALUES (:user_id, :fullname, :delivery_method, :address, :total_amount)";
    // $stmt = $connection->prepare($insertQuery);
    // $stmt->execute(['user_id' => $user_id, 'fullname' => $fullname, 'delivery_method' => $deliveryMethod, 'address' => $address, 'total_amount' => $totalAmount]);

    echo "<script>
            window.onload = function() {
                showSuccessModal('$fullname', '$deliveryMethod', '$address', '$totalAmount');
            };
          </script>";
}
?>

<div class="ofr w py">
    <h2>Оформление заказа</h2>
    <div class="oformlenie">
        <div class="oformlenie_content">
            <div class="oformlenie_form">
                <form action="" method="POST">
                    <div class="form_p">
                        <h3>ФИО получателя</h3>
                        <input type="text" name="fullname" required>
                    </div>
                    <div class="form_p">
                        <h3>Выберите способ получения заказа</h3>
                        <div class="vibrs">
                            <div class="vibr">
                                <input type="radio" name="delivery_method" value="delivery" required onclick="document.getElementById('address_field').style.display='block'">
                                <p>Доставка</p>
                            </div>
                            <div class="vibr">
                                <input type="radio" name="delivery_method" value="pickup" required onclick="document.getElementById('address_field').style.display='none'; document.getElementById('address_input').value=''">
                                <p>Самовывоз</p>
                            </div>
                        </div>
                    </div>
                    <div class="form_p" id="address_field" style="display: none;">
                        <h3>Введите адрес доставки</h3>
                        <input type="text" name="address" id="address_input" required>
                    </div>
                    <button type="submit" class="b">Оформить</button>
                </form>
            </div>
        </div>

        <div class="b_info">
            <h3>Итоговая сумма:</h3>
            <h2><?= htmlspecialchars($totalAmount) ?> ₽</h2>
            <h3>Товары в заказе:</h3>
            <ul>
                <?php foreach ($basketItems as $item): ?>
                    <p><?= htmlspecialchars($item['name']) ?> (<?= htmlspecialchars($item['quantity']) ?> шт) - <?= htmlspecialchars($item['total_price']) ?> ₽</p>
                <?php endforeach; ?>
            </ul>
            <p><?= count($basketItems) ?> товаров</p>
        </div>
    </div>
</div>


<div id="successModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h2>Заказ успешно оформлен!</h2>
        <p id="modalDetails"></p>
        <button id="okButton">Ок</button>
    </div>
</div>

<style>
.modal {
    display: none; 
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgb(0,0,0); 
    background-color: rgba(0,0,0,0.4); 
    padding-top: 60px; 
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto; 
    padding: 20px;
    border: 1px solid #888;
    width: 80%; 
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
</style>
<script>
function showSuccessModal(fullname, deliveryMethod, address, totalAmount) {
    const modalDetails = document.getElementById('modalDetails');
    modalDetails.innerHTML = `ФИО: ${fullname}<br>Способ доставки: ${deliveryMethod}${deliveryMethod === 'delivery' ? `<br>Адрес: ${address}` : ''}<br>Итоговая сумма: ${totalAmount} ₽`;
    document.getElementById('successModal').style.display = 'block';
}

document.addEventListener('DOMContentLoaded', () => {
    const closeModal = document.getElementById('closeModal');
    const okButton = document.getElementById('okButton');
    const addressField = document.getElementById('address_field');
    const addressInput = document.getElementById('address_input');

    closeModal.onclick = function() {
        document.getElementById('successModal').style.display = 'none';
    }

    okButton.onclick = function() {
        document.getElementById('successModal').style.display = 'none';
        window.location.href = 'index.php'; 
    }


    const deliveryMethodRadios = document.querySelectorAll('input[name="delivery_method"]');
    deliveryMethodRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'pickup') {
                addressField.style.display = 'none';
                addressInput.value = '';
                addressInput.removeAttribute('required'); 
            } else {
                addressField.style.display = 'block';
                addressInput.setAttribute('required', 'required'); 
            }
        });
    });

    window.onclick = function(event) {
        if (event.target == document.getElementById('successModal')) {
            document.getElementById('successModal').style.display = 'none';
        }
    }
});
</script>