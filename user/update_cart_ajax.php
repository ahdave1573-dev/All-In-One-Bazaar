<?php
session_start();
include('../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['qty'])) {
    $id = (int)$_POST['id'];
    $qty = (int)$_POST['qty'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check stock
    $p = mysqli_fetch_assoc(mysqli_query($conn, "SELECT qty, selling_price FROM products WHERE id='$id' LIMIT 1"));
    
    if (!$p) {
        echo json_encode(['status' => 'error', 'message' => 'Product not found']);
        exit();
    }

    $available = (int)$p['qty'];
    $price = (int)$p['selling_price'];

    if ($qty <= 0) {
        unset($_SESSION['cart'][$id]);
        $qty = 0;
    } else {
        $qty = min($qty, $available);
        $_SESSION['cart'][$id] = $qty;
    }

    // Calculate new totals
    $subtotal = $price * $qty;
    
    $grandTotal = 0;
    foreach ($_SESSION['cart'] as $cid => $cqty) {
        $cp = mysqli_fetch_assoc(mysqli_query($conn, "SELECT selling_price FROM products WHERE id='$cid' LIMIT 1"));
        if ($cp) {
            $grandTotal += ($cp['selling_price'] * $cqty);
        }
    }

    echo json_encode([
        'status' => 'success',
        'qty' => $qty,
        'subtotal' => number_format($subtotal),
        'grandTotal' => number_format($grandTotal)
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
