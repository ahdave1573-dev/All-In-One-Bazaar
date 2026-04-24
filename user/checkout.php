<?php
session_start();

/* ================= ERROR REPORTING ================= */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ================= CONFIG ================= */
// Adjust paths as per your folder structure
$path_to_root = (file_exists('../config/constants.php')) ? '../' : ''; 

include($path_to_root . 'config/constants.php');
include($path_to_root . 'config/db.php');
include($path_to_root . 'config/auth.php');

/* ================= LOGIN CHECK ================= */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ================= CART CHECK ================= */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* ================= PLACE ORDER LOGIC ================= */
if (isset($_POST['place_order_btn'])) {

    if (empty($_SESSION['cart'])) {
        die("ERROR: Cart is empty");
    }

    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_mode']);

    $tracking_no = "ORD" . rand(10000, 99999);

    /* --- CALC TOTAL --- */
    $total_price = 0;
    foreach ($_SESSION['cart'] as $pid => $qty) {
        $q = mysqli_query($conn,"SELECT selling_price FROM products WHERE id='$pid'");
        $pd = mysqli_fetch_assoc($q);
        if(!$pd) { unset($_SESSION['cart'][$pid]); continue; }
        $total_price += $pd['selling_price'] * $qty;
    }

    /* --- INSERT ORDER --- */
    mysqli_query($conn,"
        INSERT INTO orders 
        (user_id, tracking_no, total_price, address, payment_method, status) 
        VALUES 
        ('$user_id','$tracking_no','$total_price','$address','$payment_method','Pending')
    ");

    $order_id = mysqli_insert_id($conn);

    /* --- INSERT ITEMS --- */
    foreach ($_SESSION['cart'] as $pid => $qty) {
        $p = mysqli_query($conn,"SELECT name,selling_price FROM products WHERE id='$pid'");
        $pd = mysqli_fetch_assoc($p);
        if(!$pd) continue;

        mysqli_query($conn,"
            INSERT INTO order_items 
            (order_id, product_id, product_name, quantity, price) 
            VALUES 
            ('$order_id','$pid','{$pd['name']}','$qty','{$pd['selling_price']}')
        ");
    }

    unset($_SESSION['cart']);
    $_SESSION['order_success'] = "Order Confirmed Successfully!";
    header("Location: ../user/orders.php"); // Adjust redirection as needed
    exit();
}

include('../includes/header.php');
?>

<style>
    :root {
        --primary: #2563eb;
        --primary-dark: #1d4ed8;
        --bg-light: #f8fafc;
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-gray: #64748b;
    }

    .checkout-page {
        background: var(--bg-light);
        padding: 40px 0;
        min-height: 80vh;
        font-family: 'Poppins', sans-serif;
    }

    .checkout-container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* LAYOUT GRID */
    .checkout-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr; /* Left side wider than right */
        gap: 30px;
        align-items: start;
    }

    /* CARDS */
    .checkout-card {
        background: #fff;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid var(--border-color);
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
    }

    /* FORM ELEMENTS */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--text-dark);
        font-size: 0.95rem;
    }

    .custom-input, .custom-select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 0.95rem;
        transition: 0.3s;
        background: #fff;
        font-family: inherit;
    }

    .custom-input:focus, .custom-select:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    textarea.custom-input {
        resize: vertical;
        min-height: 120px;
    }

    /* SUMMARY LIST */
    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px dashed var(--border-color);
        font-size: 0.95rem;
        color: var(--text-gray);
    }

    .order-item strong {
        color: var(--text-dark);
    }

    .total-section {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 2px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .total-label {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-dark);
    }

    .total-price {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--primary);
    }

    /* BUTTON */
    .btn-place-order {
        background: var(--primary);
        color: white;
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 20px;
    }

    .btn-place-order:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }

    /* EMPTY CART */
    .empty-cart-msg {
        text-align: center;
        padding: 50px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .checkout-grid {
            grid-template-columns: 1fr; /* Stack on mobile */
        }
    }
</style>

<div class="checkout-page">
    <div class="checkout-container">

        <?php if (empty($_SESSION['cart'])): ?>
            <div class="empty-cart-msg">
                <h3>Your cart is empty 🛒</h3>
                <p style="color:#64748b; margin: 10px 0 20px;">Add some great products to proceed.</p>
                <a href="../products.php" class="btn-place-order" style="display:inline-block; width:auto; padding: 10px 30px; text-decoration:none;">Go to Shop</a>
            </div>
        <?php else: ?>

            <form method="POST">
                <div class="checkout-grid">

                    <div class="checkout-card">
                        <h4 class="card-title"><i class="fas fa-truck"></i> Delivery Information</h4>
                        
                        <div class="form-group">
                            <label class="form-label">Full Shipping Address</label>
                            <textarea name="address" class="custom-input" placeholder="House No, Street Name, City, Pincode..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_mode" class="custom-select">
                                <option value="COD">Cash on Delivery (COD)</option>
                                <option value="UPI">UPI / QR Code</option>
                                <option value="CARD">Credit / Debit Card</option>
                            </select>
                        </div>
                    </div>

                    <div class="checkout-card">
                        <h4 class="card-title"><i class="fas fa-shopping-bag"></i> Order Summary</h4>

                        <div class="order-list">
                            <?php
                            $grand_total = 0;
                            foreach ($_SESSION['cart'] as $pid => $qty) {
                                $p = mysqli_query($conn,"SELECT name, selling_price FROM products WHERE id='$pid'");
                                $pd = mysqli_fetch_assoc($p);
                                if(!$pd) { unset($_SESSION['cart'][$pid]); continue; }
                                $sub_total = $pd['selling_price'] * $qty;
                                $grand_total += $sub_total;
                            ?>
                                <div class="order-item">
                                    <span>
                                        <strong><?= htmlspecialchars($pd['name']); ?></strong> 
                                        <span style="font-size:0.85em; color:#94a3b8;">x <?= $qty; ?></span>
                                    </span>
                                    <span>₹<?= number_format($sub_total); ?></span>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="total-section">
                            <span class="total-label">Grand Total</span>
                            <span class="total-price">₹<?= number_format($grand_total); ?></span>
                        </div>

                        <button type="submit" name="place_order_btn" class="btn-place-order">
                            Confirm & Place Order <i class="fas fa-arrow-right" style="margin-left:5px;"></i>
                        </button>
                    </div>

                </div>
            </form>

        <?php endif; ?>

    </div>
</div>

<?php include('../includes/footer.php'); ?>