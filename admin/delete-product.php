<?php
require_once('includes/auth_check.php');
include("../config/db.php");

// Check product id
if(!isset($_GET['id'])){
    header("Location: manage-products.php");
    exit();
}

$id = $_GET['id'];

// Get product image
$res = mysqli_query($conn, "SELECT image FROM products WHERE id='$id'");
$product = mysqli_fetch_assoc($res);

// Delete image file if exists
if($product && $product['image'] != ""){
    $imgPath = "../assets/images/products/".$product['image'];
    if(file_exists($imgPath)){
        unlink($imgPath);
    }
}

// Delete product from DB
mysqli_query($conn, "DELETE FROM products WHERE id='$id'");

// Redirect back
header("Location: manage-products.php");
exit();
?>
