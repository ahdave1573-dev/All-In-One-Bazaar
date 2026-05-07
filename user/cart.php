<?php
session_start();

include('../config/constants.php');
include('../config/db.php');

/* ================= CART INIT ================= */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* ================= ADD TO CART (HARD STOCK LIMIT 🔥) ================= */
if (isset($_GET['add'])) {

    $id  = (int)$_GET['add'];
    $qty = isset($_GET['qty']) ? (int)$_GET['qty'] : 1;

    $p = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT qty FROM products WHERE id='$id' LIMIT 1")
    );

    if (!$p || $p['qty'] <= 0) {
        $_SESSION['cart_error'] = "Product is out of stock.";
        header("Location: cart.php");
        exit();
    }

    $existing = $_SESSION['cart'][$id] ?? 0;
    $_SESSION['cart'][$id] = min($existing + $qty, $p['qty']);

    if ($existing + $qty > $p['qty']) {
        $_SESSION['cart_error'] = "Quantity adjusted due to stock limit.";
    }

    if (isset($_GET['checkout']) && $_GET['checkout'] == 1) {
        header("Location: checkout.php");
    } else {
        header("Location: cart.php");
    }
    exit();
}

/* ================= REMOVE ITEM ================= */
if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    header("Location: cart.php");
    exit();
}

/* ================= UPDATE CART (HARD STOCK LIMIT 🔥) ================= */
if (isset($_POST['update_cart']) && isset($_POST['qty'])) {

    foreach ($_POST['qty'] as $id => $qty) {

        $qty = (int)$qty;

        $p = mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT qty FROM products WHERE id='$id' LIMIT 1")
        );

        if (!$p || $p['qty'] <= 0 || $qty <= 0) {
            unset($_SESSION['cart'][$id]);
            continue;
        }

        if ($qty > $p['qty']) {
            $_SESSION['cart'][$id] = $p['qty'];
            $_SESSION['cart_error'] = "Some quantities were adjusted to available stock.";
        } else {
            $_SESSION['cart'][$id] = $qty;
        }
    }

    $_SESSION['cart_success'] = "Cart updated successfully.";
    header("Location: cart.php");
    exit();
}

/* ================= HTML OUTPUT ================= */
include('../includes/header.php');
?>

<style>
.cart-container{max-width:1200px;margin:50px auto;padding:0 20px}
.page-title{font-size:2rem;margin-bottom:30px}
.cart-table{width:100%;border-collapse:collapse;background:#fff}
.cart-table th,.cart-table td{padding:18px;border-bottom:1px solid #eee;text-align:left}
.cart-table th:nth-child(2),.cart-table td:nth-child(2),
.cart-table th:nth-child(3),.cart-table td:nth-child(3),
.cart-table th:nth-child(4),.cart-table td:nth-child(4){text-align:center}
.cart-product{display:flex;align-items:center;gap:15px}
.cart-img{width:70px;height:70px;object-fit:contain}
.qty-control{display:flex;align-items:center;border:1px solid #ddd;border-radius:5px;overflow:hidden;width:fit-content;margin:0 auto}
.qty-btn{background:#f8f9fa;border:none;padding:5px 12px;cursor:pointer;font-weight:700;font-size:1.1rem;transition:background 0.2s}
.qty-btn:hover{background:#e9ecef}
.qty-input{width:45px;text-align:center;border:none;border-left:1px solid #ddd;border-right:1px solid #ddd;font-weight:600;pointer-events:none}
/* Hide number arrows */
.qty-input::-webkit-inner-spin-button,.qty-input::-webkit-outer-spin-button{-webkit-appearance:none;margin:0}
.cart-actions{display:flex;justify-content:space-between;margin-top:30px;gap:20px}
.checkout-box{background:#fff;padding:25px;border-radius:10px;max-width:400px;width:100%}
.summary-row,.summary-total{display:flex;justify-content:space-between;margin-bottom:10px}
.summary-total{font-weight:700;font-size:1.2rem}
.btn-update{border:1px solid #2563eb;color:#2563eb;padding:10px 20px;background:none;cursor:pointer}
.btn-checkout{display:block;background:#2563eb;color:#fff;text-align:center;padding:14px;border-radius:8px;margin-top:20px;text-decoration:none}
.empty-cart{text-align:center;padding:60px}
.msg-error{color:#b91c1c;margin-bottom:15px;font-weight:600}
.msg-success{color:#047857;margin-bottom:15px;font-weight:600}
</style>

<div class="cart-container">
<h1 class="page-title">Shopping Cart</h1>

<?php
if(isset($_SESSION['cart_error'])){
    echo "<div class='msg-error'>".$_SESSION['cart_error']."</div>";
    unset($_SESSION['cart_error']);
}
if(isset($_SESSION['cart_success'])){
    echo "<div class='msg-success'>".$_SESSION['cart_success']."</div>";
    unset($_SESSION['cart_success']);
}
?>

<?php if (!empty($_SESSION['cart'])): ?>

<form action="cart.php" method="POST">

<table class="cart-table">
<thead>
<tr>
    <th>Product</th>
    <th>Price</th>
    <th>Qty</th>
    <th>Total</th>
</tr>
</thead>
<tbody>

<?php
$grandTotal = 0;

foreach ($_SESSION['cart'] as $id => $qty) {

    $res = mysqli_query($conn, "SELECT * FROM products WHERE id='$id'");
    if ($row = mysqli_fetch_assoc($res)) {

        if ($row['qty'] <= 0) {
            unset($_SESSION['cart'][$id]);
            continue;
        }

        $qty = min($qty, $row['qty']);
        $_SESSION['cart'][$id] = $qty;

        $price = (int)$row['selling_price'];
        $subtotal = $price * $qty;
        $grandTotal += $subtotal;

        $img = (!empty($row['image']) && file_exists(__DIR__."/../uploads/products/".$row['image']))
            ? SITEURL."uploads/products/".$row['image']
            : "https://via.placeholder.com/100x100?text=No+Image";
?>

<tr data-id="<?= $id ?>" data-price="<?= $price ?>">
    <td>
        <div class="cart-product">
            <img src="<?= $img ?>" class="cart-img">
            <div>
                <strong><?= htmlspecialchars($row['name']) ?></strong><br>
                <small style="color:#64748b">Available: <?= $row['qty'] ?></small><br>
                <a href="cart.php?remove=<?= $id ?>" style="color:red">Remove</a>
            </div>
        </div>
    </td>
    <td>₹<?= number_format($price) ?></td>
    <td>
        <div class="qty-control">
            <button type="button" class="qty-btn btn-minus" onclick="updateQty(<?= $id ?>, -1)">-</button>
            <input type="number"
                   name="qty[<?= $id ?>]"
                   value="<?= $qty ?>"
                   min="1"
                   max="<?= $row['qty'] ?>"
                   class="qty-input"
                   id="qty-<?= $id ?>"
                   readonly>
            <button type="button" class="qty-btn btn-plus" onclick="updateQty(<?= $id ?>, 1)">+</button>
        </div>
    </td>
    <td>₹<span class="subtotal-val" id="subtotal-<?= $id ?>"><?= number_format($subtotal) ?></span></td>
</tr>

<?php
    }
}
?>

</tbody>
</table>

<div class="cart-actions">
    <div>
        <button type="submit" name="update_cart" class="btn-update">
            Update Cart
        </button>
        <a href="../products.php" style="margin-left:15px">Continue Shopping</a>
    </div>

    <div class="checkout-box">
        <div class="summary-row">
            <span>Subtotal</span>
            <span>₹<span id="grand-subtotal"><?= number_format($grandTotal) ?></span></span>
        </div>
        <div class="summary-row">
            <span>Shipping</span>
            <span style="color:green">Free</span>
        </div>
        <div class="summary-total">
            <span>Total</span>
            <span>₹<span id="grand-total"><?= number_format($grandTotal) ?></span></span>
        </div>

        <?php if (!empty($_SESSION['user_id'])): ?>
            <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
        <?php else: ?>
            <a href="../login.php" class="btn-checkout">Login to Checkout</a>
        <?php endif; ?>
    </div>
</div>

</form>

<script>
function updateQty(id, delta) {
    const input = document.getElementById('qty-' + id);
    let currentQty = parseInt(input.value);
    let newQty = currentQty + delta;
    
    if (newQty < 1) return;
    if (newQty > parseInt(input.max)) return;
    
    input.value = newQty;
    
    // AJAX update
    const formData = new FormData();
    formData.append('id', id);
    formData.append('qty', newQty);
    
    fetch('update_cart_ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            document.getElementById('subtotal-' + id).innerText = data.subtotal;
            document.getElementById('grand-subtotal').innerText = data.grandTotal;
            document.getElementById('grand-total').innerText = data.grandTotal;
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<?php else: ?>

<div class="empty-cart">
    <h2>Your cart is empty</h2>
    <a href="../products.php" class="btn-checkout">Start Shopping</a>
</div>

<?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>
