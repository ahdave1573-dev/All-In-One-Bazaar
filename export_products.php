<?php
include('config/db.php');

$file = __DIR__ . '/uploads/products_data.txt';
$handle = fopen($file, 'w');

fwrite($handle, "========================================\n");
fwrite($handle, "          ALL IN ONE BAZAAR PRODUCTS       \n");
fwrite($handle, "========================================\n\n");

$query = mysqli_query($conn, "SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY c.name, p.name");

$current_cat = "";

while($row = mysqli_fetch_assoc($query)) {
    if ($current_cat != $row['cat_name']) {
        $current_cat = $row['cat_name'];
        fwrite($handle, "\n----------------------------------------\n");
        fwrite($handle, "CATEGORY: " . strtoupper($current_cat) . "\n");
        fwrite($handle, "----------------------------------------\n");
    }
    
    fwrite($handle, "Product   : " . $row['name'] . "\n");
    fwrite($handle, "Price     : ₹" . $row['selling_price'] . " (Original: ₹" . $row['original_price'] . ")\n");
    fwrite($handle, "Stock     : " . $row['qty'] . "\n");
    fwrite($handle, "Image File: " . $row['image'] . "\n");
    fwrite($handle, "Summary   : " . $row['small_description'] . "\n");
    fwrite($handle, "Desc      : " . $row['description'] . "\n\n");
}

fclose($handle);
echo "Data exported to products_data.txt";
?>
