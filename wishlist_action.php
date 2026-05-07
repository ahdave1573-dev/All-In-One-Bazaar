<?php
session_start();
include('config/db.php');

header('Content-Type: application/json');

// Login check
if(!isset($_SESSION['user_id'])){
    echo json_encode(["status" => "login"]);
    exit;
}

$user_id    = intval($_SESSION['user_id']);
$product_id = intval($_POST['product_id']);

// Check already exists
$check = mysqli_query(
    $conn,
    "SELECT id FROM wishlist WHERE user_id='$user_id' AND product_id='$product_id'"
);

if(mysqli_num_rows($check) > 0){
    // Remove
    mysqli_query(
        $conn,
        "DELETE FROM wishlist WHERE user_id='$user_id' AND product_id='$product_id'"
    );
    echo json_encode(["status" => "removed"]);
} else {
    // Insert
    mysqli_query(
        $conn,
        "INSERT INTO wishlist (user_id, product_id) VALUES ('$user_id','$product_id')"
    );
    echo json_encode(["status" => "added"]);
}
