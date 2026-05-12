<?php
include('config/db.php');
$res = mysqli_query($conn, "SELECT id, name, image FROM products LIMIT 20");
while($row = mysqli_fetch_assoc($res)) {
    echo "ID: " . $row['id'] . " | Name: " . $row['name'] . " | Image: " . $row['image'] . "\n";
}
?>
