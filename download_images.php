<?php
/**
 * All In One Bazaar - Product Image Downloader
 * Downloads free stock images for all products and updates the database
 * Run this in browser: http://localhost/All In One Bazaar/download_images.php
 */

set_time_limit(300); // 5 minutes max
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('config/db.php');

$upload_dir = __DIR__ . '/uploads/products/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Image URLs mapped by product slug (free Unsplash images)
$image_map = [
    // MOBILE
    'samsung-galaxy-s24-ultra' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=500&h=500&fit=crop',
    'iphone-15-pro-max' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=500&h=500&fit=crop',
    'oneplus-12' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500&h=500&fit=crop',
    'redmi-note-13-pro' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=500&fit=crop',
    'realme-gt-5-pro' => 'https://images.unsplash.com/photo-1565849904461-04a58ad377e0?w=500&h=500&fit=crop',
    
    // LAPTOP
    'macbook-air-m3' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=500&h=500&fit=crop',
    'hp-pavilion-15' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop',
    'dell-inspiron-14' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=500&h=500&fit=crop',
    'asus-rog-strix-g16' => 'https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?w=500&h=500&fit=crop',
    'lenovo-ideapad-slim-3' => 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=500&h=500&fit=crop',
    
    // FASHION
    'men-slim-fit-casual-shirt' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=500&h=500&fit=crop',
    'women-floral-kurti-set' => 'https://images.unsplash.com/photo-1583391733956-6c78276477e2?w=500&h=500&fit=crop',
    'men-denim-jeans' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=500&h=500&fit=crop',
    'women-western-dress' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=500&h=500&fit=crop',
    'unisex-hoodie-sweatshirt' => 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=500&h=500&fit=crop',
    
    // HOME & KITCHEN
    'prestige-induction-cooktop' => 'https://images.unsplash.com/photo-1585515320310-259814833e62?w=500&h=500&fit=crop',
    'pigeon-mixer-grinder' => 'https://images.unsplash.com/photo-1570222094114-d054a817e56b?w=500&h=500&fit=crop',
    'cotton-bedsheet-king' => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=500&h=500&fit=crop',
    'cello-water-bottle-set' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=500&h=500&fit=crop',
    'philips-air-fryer' => 'https://images.unsplash.com/photo-1648733966427-0e3afe67a1df?w=500&h=500&fit=crop',
    
    // BOOKS
    'atomic-habits' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=500&h=500&fit=crop',
    'psychology-of-money' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=500&h=500&fit=crop',
    'rich-dad-poor-dad' => 'https://images.unsplash.com/photo-1524578271613-d550eacf6090?w=500&h=500&fit=crop',
    'wings-of-fire' => 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?w=500&h=500&fit=crop',
    'harry-potter-box-set' => 'https://images.unsplash.com/photo-1618666012174-83b441a6e85e?w=500&h=500&fit=crop',
    
    // SPORTS
    'nivia-storm-football' => 'https://images.unsplash.com/photo-1614632537197-38a17061c2bd?w=500&h=500&fit=crop',
    'yonex-badminton-racket' => 'https://images.unsplash.com/photo-1617083934555-ac7b4d0c8be8?w=500&h=500&fit=crop',
    'boldfit-yoga-mat' => 'https://images.unsplash.com/photo-1592432678016-e910b452f9a2?w=500&h=500&fit=crop',
    'sg-cricket-bat' => 'https://images.unsplash.com/photo-1531415074968-036ba1b575da?w=500&h=500&fit=crop',
    'fitbit-inspire-3' => 'https://images.unsplash.com/photo-1575311373937-040b8e1fd5b6?w=500&h=500&fit=crop',
    
    // BEAUTY
    'lakme-skin-dew-serum' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=500&h=500&fit=crop',
    'nivea-men-face-wash' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=500&h=500&fit=crop',
    'biotique-hair-serum' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=500&h=500&fit=crop',
    'maybelline-lipstick-set' => 'https://images.unsplash.com/photo-1586495777744-4413f21062fa?w=500&h=500&fit=crop',
    'philips-trimmer' => 'https://images.unsplash.com/photo-1621607512214-68297480165e?w=500&h=500&fit=crop',
    
    // SHOES & HANDBAGS
    'nike-air-max-270' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&h=500&fit=crop',
    'puma-softride-sneakers' => 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=500&h=500&fit=crop',
    'lavie-women-handbag' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=500&h=500&fit=crop',
    'wildcraft-laptop-backpack' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500&h=500&fit=crop',
    'woodland-men-sandals' => 'https://images.unsplash.com/photo-1603487742131-4160ec999306?w=500&h=500&fit=crop',
    
    // TOYS & GAMES
    'lego-classic-creative-box' => 'https://images.unsplash.com/photo-1587654780291-39c9404d7dd0?w=500&h=500&fit=crop',
    'hot-wheels-10-car-pack' => 'https://images.unsplash.com/photo-1594787318286-3d835c1d207f?w=500&h=500&fit=crop',
    'monopoly-board-game' => 'https://images.unsplash.com/photo-1632501641765-e568d28b0015?w=500&h=500&fit=crop',
    'rubik-cube-3x3' => 'https://images.unsplash.com/photo-1577401239170-897c2bc148c4?w=500&h=500&fit=crop',
    'nerf-elite-blaster' => 'https://images.unsplash.com/photo-1596461404969-9ae70f2830c1?w=500&h=500&fit=crop',
    
    // GROCERY
    'cadbury-celebration-gift' => 'https://images.unsplash.com/photo-1549007994-cb92caebd54b?w=500&h=500&fit=crop',
    'tata-gold-tea' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=500&h=500&fit=crop',
    'saffola-gold-oil-5l' => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=500&h=500&fit=crop',
    'wow-momo-frozen-momos' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=500&h=500&fit=crop',
    'happilo-dry-fruits-mix' => 'https://images.unsplash.com/photo-1596591868264-b7b2e9f65e95?w=500&h=500&fit=crop',
    
    // JEWELRY & WATCHES
    'titan-karishma-watch' => 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=500&h=500&fit=crop',
    'fastrack-reflex-smartwatch' => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=500&h=500&fit=crop',
    'tanishq-gold-pendant' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=500&h=500&fit=crop',
    'peora-silver-earrings' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=500&h=500&fit=crop',
    'noise-colorfit-ultra-3' => 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=500&h=500&fit=crop',
    
    // ELECTRONICS
    'sony-wh1000xm5' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop',
    'samsung-55-4k-tv' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=500&h=500&fit=crop',
    'boat-airdopes-141' => 'https://images.unsplash.com/photo-1590658268037-6bf12f032f55?w=500&h=500&fit=crop',
    'jbl-flip-6-speaker' => 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=500&h=500&fit=crop',
    'canon-eos-m50-ii' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=500&h=500&fit=crop',
    
    // HEALTH
    'ensure-nutrition-400g' => 'https://images.unsplash.com/photo-1550572017-edd951aa8f72?w=500&h=500&fit=crop',
    'dr-morepen-bp-monitor' => 'https://images.unsplash.com/photo-1559757175-5700dde675bc?w=500&h=500&fit=crop',
    'himalaya-ashwagandha' => 'https://images.unsplash.com/photo-1471864190281-a93a3070b6de?w=500&h=500&fit=crop',
    'healthkart-whey-protein' => 'https://images.unsplash.com/photo-1593095948071-474c5cc2c3cf?w=500&h=500&fit=crop',
    'dettol-sanitizer-500ml' => 'https://images.unsplash.com/photo-1584483766114-2cea6facdf57?w=500&h=500&fit=crop',
];

echo "<h2>All In One Bazaar - Product Image Downloader</h2>";
echo "<p>Downloading images for all products...</p><hr>";

$success = 0;
$failed = 0;

// Get all products
$products = mysqli_query($conn, "SELECT id, slug, name FROM products ORDER BY id");

while ($row = mysqli_fetch_assoc($products)) {
    $slug = $row['slug'];
    $name = $row['name'];
    $id = $row['id'];
    
    if (!isset($image_map[$slug])) {
        echo "<p style='color:orange'>⚠️ No image mapped for: $name ($slug)</p>";
        $failed++;
        continue;
    }
    
    $url = $image_map[$slug];
    $filename = $slug . '.jpg';
    $filepath = $upload_dir . $filename;
    
    // Download image
    $ctx = stream_context_create([
        'http' => [
            'timeout' => 15,
            'header' => "User-Agent: Mozilla/5.0\r\n"
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ]
    ]);
    
    $img_data = @file_get_contents($url, false, $ctx);
    
    if ($img_data && strlen($img_data) > 1000) {
        file_put_contents($filepath, $img_data);
        
        // Update database
        mysqli_query($conn, "UPDATE products SET image='$filename' WHERE id='$id'");
        
        echo "<p style='color:green'>✅ $name → $filename (" . round(strlen($img_data)/1024) . " KB)</p>";
        $success++;
    } else {
        echo "<p style='color:red'>❌ Failed to download: $name</p>";
        $failed++;
    }
    
    // Small delay to be respectful
    usleep(200000); // 0.2 sec
}

echo "<hr>";
echo "<h3>Done! $success downloaded, $failed failed</h3>";
echo "<p><a href='index.php'>← Go to Homepage</a></p>";

// Create ZIP
$zip_path = __DIR__ . '/uploads/product_images.zip';
if (class_exists('ZipArchive')) {
    $zip = new ZipArchive();
    if ($zip->open($zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        $files = glob($upload_dir . '*.jpg');
        foreach ($files as $file) {
            $zip->addFile($file, 'product_images/' . basename($file));
        }
        $zip->close();
        echo "<p>📦 <strong>ZIP file created:</strong> <a href='uploads/product_images.zip' download>Download product_images.zip</a></p>";
    }
}
?>
