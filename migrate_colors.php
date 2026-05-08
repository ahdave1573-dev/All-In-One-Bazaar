<?php
// 1. UPDATE HEADER.PHP
$headerFile = 'includes/header.php';
$content = file_get_contents($headerFile);

// Replace the new CSS root variables and Navbar colors
$newCSS = '<style>
/* ::::: CUSTOM COLOR PALETTE VARIABLES ::::: */
:root {
    --primary: #1A73E8; 
    --primary-dark: #1256ae; 
    --accent: #FF6B00; 
    --accent-dark: #e65c00;
    --dark: #202124; 
    --light: #F8F9FA;
    --white: #ffffff; 
    --gray: #5f6368; 
    --amazon-orange: #FF6B00;
    --bg-color: #F8F9FA;
    --glass-bg: rgba(26, 115, 232, 0.95); /* Royal Blue Navbar */
    --shadow-3d: 0 10px 30px rgba(0, 0, 0, 0.1), inset 0 1px 0 rgba(255,255,255,0.2);
    --shadow-float: 0 20px 40px rgba(0, 0, 0, 0.15), inset 0 2px 0 rgba(255,255,255,0.3);
}
* { margin:0; padding:0; box-sizing:border-box; font-family:"Poppins", sans-serif; }
body { background:var(--bg-color); color:var(--dark); display:flex; flex-direction:column; min-height:100vh; overflow-x:hidden; }
.bg-orb { display:none; } /* Orbs disabled for clean look */
h1, h2, h3, h4, h5, h6 { color: var(--dark); }

a { text-decoration:none; color:inherit; } ul { list-style:none; }
.top-bar { background:var(--dark); color:#e2e8f0; font-size:0.8rem; padding:8px 0; box-shadow:0 4px 15px rgba(0,0,0,0.3); z-index:1001; position:relative; }
.top-bar-inner { max-width:1200px; margin:0 auto; padding:0 20px; display:flex; justify-content:space-between; align-items:center; }
.top-bar a { transition:0.3s; padding:4px 8px; border-radius:6px; }
.top-bar a:hover { background:rgba(255,255,255,0.1); color:var(--accent); transform:translateY(-2px); display:inline-block; }
.top-bar-links { display:flex; gap:15px; }

/* ::::: ROYAL BLUE NAVBAR ::::: */
.navbar { background:var(--glass-bg); backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px); box-shadow:var(--shadow-3d); position:sticky; top:0; z-index:1000; width:100%; transition:0.4s; }
.nav-container { max-width:1200px; margin:0 auto; padding:12px 20px; display:flex; justify-content:space-between; align-items:center; gap:20px; }
.logo { font-size:26px; font-weight:800; color:var(--white); letter-spacing:-0.5px; }
.logo span { color:var(--accent); }

/* ::::: 3D SEARCH BAR ::::: */
.nav-search { flex:1; max-width:600px; display:flex; align-items:center; background:#fff; border-radius:8px; box-shadow:0 5px 15px rgba(0,0,0,0.1); overflow:hidden; }
.nav-search select { border:none; background:#f1f3f4; padding:12px 10px; font-size:0.85rem; color:var(--dark); outline:none; border-right:1px solid #e2e8f0; cursor:pointer; min-width:110px; font-weight:600; }
.nav-search input { flex:1; border:none; padding:12px 15px; font-size:0.95rem; outline:none; background:transparent; color: var(--dark); }
.nav-search button { background:var(--accent); color:#fff; border:none; padding:0 25px; font-size:1.1rem; cursor:pointer; transition:0.3s; height:100%; display:flex; align-items:center; }
.nav-search button:hover { background:var(--accent-dark); }

/* Navigation Links - White since background is blue */
.nav-links { display:flex; gap:25px; }
.nav-links li a { font-size:15px; font-weight:600; color:#e8eaed; transition:0.3s; padding:8px 12px; border-radius:10px; position:relative; }
.nav-links li a::after { content:""; position:absolute; bottom:0; left:50%; width:0; height:3px; background:var(--accent); transition:0.3s; border-radius:3px; transform:translateX(-50%); }
.nav-links li a:hover { color:var(--white); background:rgba(255,255,255,0.1); transform:translateY(-2px); }
.nav-links li a:hover::after, .nav-links li a.active::after { width:80%; }
.nav-links li a.active { color:var(--white); }

/* Icons Area */
.nav-icons { display:flex; align-items:center; gap:20px; perspective:1000px; }
.nav-icons > a, .user-dropdown { font-size:1.25rem; color:var(--primary); transition:0.4s; display:flex; align-items:center; justify-content:center; width:45px; height:45px; background:var(--white); border-radius:50%; box-shadow:0 4px 10px rgba(0,0,0,0.1); cursor:pointer; }
.nav-icons > a:hover, .user-dropdown:hover { color:var(--accent); transform:translateY(-3px); box-shadow:0 10px 20px rgba(0,0,0,0.2); }
.cart-icon-wrapper { position:relative; }
.cart-badge { position:absolute; top:-5px; right:-5px; background:var(--accent); color:var(--white); font-size:11px; font-weight:800; height:22px; width:22px; display:flex; align-items:center; justify-content:center; border-radius:50%; border:2px solid var(--white); box-shadow:0 4px 8px rgba(0,0,0,0.2); }

/* ::::: CATEGORY BAR ::::: */
.category-bar { background:var(--dark); box-shadow:inset 0 2px 10px rgba(0,0,0,0.3); width:100%; overflow:hidden; position:relative; }
.category-bar-inner { max-width:1200px; margin:0 auto; display:flex; align-items:center; padding:5px 10px; overflow-x:auto; scrollbar-width:none; }
.category-bar-inner::-webkit-scrollbar { display:none; }
.cat-bar-item { color:#e8eaed; font-size:0.85rem; font-weight:600; padding:10px 18px; margin:5px; border-radius:8px; white-space:nowrap; transition:0.3s; display:flex; align-items:center; gap:8px; border-bottom: 2px solid transparent; }
.cat-bar-item:hover { background:rgba(255,255,255,0.1); color:#fff; border-bottom-color:var(--accent); }

/* Dropdown */
.dropdown-content { display:block; visibility:hidden; opacity:0; position:absolute; right:0; top:120%; background:var(--white); min-width:220px; box-shadow:0 15px 35px rgba(0,0,0,0.2); border-radius:8px; overflow:hidden; z-index:1001; transition:0.3s; }
.user-dropdown.show .dropdown-content { visibility:visible; opacity:1; transform:translateY(0); }
.dropdown-content a { color:var(--dark); padding:14px 20px; display:flex; align-items:center; gap:12px; font-size:14px; font-weight:500; border-bottom:1px solid #f1f3f4; transition:0.2s; }
.dropdown-content a:hover { background:#f8f9fa; color:var(--primary); padding-left:25px; }

.user-name-span { font-size:0.9rem; font-weight:700; display:none; }
@media(min-width:768px){ .user-name-span { display:block; } }
.hamburger { display:none; background:none; border:none; font-size:1.5rem; color:var(--white); cursor:pointer; }
.mobile-menu-overlay { display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:9998; backdrop-filter:blur(5px); }
.mobile-menu-overlay.show { display:block; }
.mobile-menu { position:fixed; top:0; left:-320px; width:300px; height:100vh; background:var(--white); z-index:9999; transition:0.3s; overflow-y:auto; box-shadow:10px 0 25px rgba(0,0,0,0.2); }
.mobile-menu.show { left:0; }
.mobile-menu-header { background:var(--primary); color:#fff; padding:25px 20px; display:flex; justify-content:space-between; align-items:center; }
.mobile-menu-header h3 { font-size:1.2rem; display:flex; align-items:center; gap:10px; }
.mobile-menu-close { background:rgba(255,255,255,0.2); border:none; color:#fff; width:35px; height:35px; border-radius:50%; font-size:1.2rem; cursor:pointer; }
.mobile-nav-links { padding:10px 0; }
.mobile-nav-links a { display:flex; align-items:center; gap:15px; padding:15px 25px; color:var(--dark); font-size:1rem; font-weight:500; border-bottom:1px solid #f1f3f4; transition:0.3s; }
.mobile-nav-links a:hover { background:#f8f9fa; color:var(--primary); padding-left:35px; }
.mobile-nav-links a i { width:25px; text-align:center; color:var(--primary); }
.mobile-cat-title { padding:20px 25px 10px; font-size:0.8rem; font-weight:800; text-transform:uppercase; color:var(--gray); }
@media(max-width:991px){ .nav-links,.nav-search{display:none !important;} .hamburger{display:block;} .navbar{position:relative; border-radius:0;} .category-bar{display:none;} }
</style>';

$content = preg_replace('/<style>.*?<\/style>/is', $newCSS, $content);
file_put_contents($headerFile, $content);
echo "Header customized.\n";

// 2. UPDATE INDEX.PHP OVERRIDES
$indexFile = 'index.php';
$content2 = file_get_contents($indexFile);

$overrides = '
    /* ::::: 3D OVERRIDES - DEEP ORANGE BUTTONS ::::: */
    .slider-container { background: transparent !important; }
    
    .product-grid { perspective: 1000px; }
    .cat-card, .product-card, .why-card, .feature-item {
        background: #ffffff !important;
        border: 1px solid #e8eaed !important;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05) !important;
        transform-style: preserve-3d;
        transition: all 0.3s ease !important;
        border-radius: 12px !important;
    }
    .cat-card:hover, .product-card:hover, .why-card:hover, .feature-item:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        border-color: var(--primary) !important;
    }
    
    .section-container { background: transparent !important; border: none !important; backdrop-filter: none !important; }
    h2 span { color: var(--primary) !important; }
    
    /* 3D Tactile "Lock" Buttons (DEEP ORANGE) */
    .hero-btn, .promo-btn, .save-btn, .view-all-btn {
        background: var(--accent) !important;
        color: white !important;
        border: none !important;
        box-shadow: 0 4px 0 var(--accent-dark), 0 8px 15px rgba(255,107,0,0.3) !important;
        border-radius: 8px !important;
        padding: 10px 24px !important;
        transition: 0.15s !important;
        font-weight: 700 !important;
        transform: translateY(0) !important;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .hero-btn:active, .promo-btn:active, .save-btn:active, .view-all-btn:active {
        transform: translateY(4px) !important;
        box-shadow: 0 0 0 var(--accent-dark), 0 4px 8px rgba(255,107,0,0.3) !important;
    }
    
    .view-btn { background: var(--accent) !important; border-color: var(--white) !important; }
    .view-btn:hover { background: var(--accent-dark) !important; }
</style>';

// Replace the closing </style> tag in index.php with the new overrides
$content2 = preg_replace('/\/\* ::::: 3D OVERRIDES ::::: \*\/.*?<\/style>/is', $overrides, $content2);
file_put_contents($indexFile, $content2);
echo "Index customized.\n";
?>
