# 🛒 All In One Bazaar

<div align="center">

![All In One Bazaar Banner](https://img.shields.io/badge/All%20In%20One-Bazaar-orange?style=for-the-badge&logo=shopify&logoColor=white)

[![Live Website](https://img.shields.io/badge/🌐%20Live%20Website-allinonebazaar.infinityfreeapp.com-blue?style=for-the-badge)](http://allinonebazaar.infinityfreeapp.com)
[![GitHub Repo](https://img.shields.io/badge/GitHub-Repository-black?style=for-the-badge&logo=github)](https://github.com/ahdave1573-dev/All-In-One-Bazaar)
[![Status](https://img.shields.io/badge/Status-Live-brightgreen?style=for-the-badge)]()
[![PHP](https://img.shields.io/badge/PHP-Backend-777BB4?style=for-the-badge&logo=php&logoColor=white)]()
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)]()

</div>

---

## 📌 Project Overview

**All In One Bazaar** is a full-featured online shopping platform where customers can browse, search, and purchase products from multiple categories — all in one place. The platform provides a seamless shopping experience with user authentication, product management, cart functionality, and order processing.

> Built with love by **Anshul Dave** 🚀

---

## 🌐 Live Demo

🔗 **Live Website:** [https://allinonebazaar.infinityfreeapp.com](https://allinonebazaar.infinityfreeapp.com)

> Click the link above to visit the live running website! 🚀

---

## ✨ Features

### 👤 User Side
- 🔐 User Registration & Login (with session management)
- 🏠 Home Page with featured & latest products
- 🔍 Product Search & Filter by Category
- 🛍️ Product Detail Page
- 🛒 Add to Cart / Remove from Cart
- 📦 Order Placement & Order History
- 👤 User Profile Management
- 📱 Responsive Design (Mobile Friendly)

### 🔧 Admin Panel
- 📊 Admin Dashboard with overview stats
- ➕ Add / Edit / Delete Products
- 📁 Category Management
- 📋 View & Manage Orders
- 👥 User Management
- 🖼️ Image Upload for Products

---

## 🛠️ Tech Stack

| Layer        | Technology                        |
|--------------|-----------------------------------|
| Frontend     | HTML5, CSS3, JavaScript           |
| Backend      | PHP                               |
| Database     | MySQL                             |
| Styling      | Bootstrap / Custom CSS            |
| Hosting      | InfinityFree (Free Web Hosting)   |
| Version Control | Git & GitHub                   |

---

## 📁 Project Structure

```
All-In-One-Bazaar/
│
├── index.php                  # Home Page
├── login.php                  # User Login
├── register.php               # User Registration
├── logout.php                 # Logout Handler
│
├── products.php               # All Products Listing
├── product-detail.php         # Single Product View
├── search.php                 # Search Results Page
│
├── cart.php                   # Shopping Cart
├── checkout.php               # Checkout Page
├── order-success.php          # Order Confirmation
│
├── profile.php                # User Profile
├── my-orders.php              # User Order History
│
├── admin/
│   ├── index.php              # Admin Dashboard
│   ├── products.php           # Manage Products
│   ├── add-product.php        # Add New Product
│   ├── edit-product.php       # Edit Product
│   ├── categories.php         # Manage Categories
│   ├── orders.php             # Manage Orders
│   └── users.php              # Manage Users
│
├── includes/
│   ├── db.php                 # Database Connection
│   ├── header.php             # Common Header
│   ├── footer.php             # Common Footer
│   └── functions.php          # Helper Functions
│
├── assets/
│   ├── css/                   # Stylesheets
│   ├── js/                    # JavaScript Files
│   └── images/                # Static Images
│
├── uploads/                   # Product Images (Uploaded)
│
└── README.md                  # Project Documentation
```

---

## ⚙️ Installation & Setup

Follow these steps to run the project locally on your machine:

### ✅ Prerequisites

- [XAMPP](https://www.apachefriends.org/) or any PHP server (WAMP, LAMP, MAMP)
- PHP >= 7.4
- MySQL >= 5.7
- Git

### 📥 Step 1: Clone the Repository

```bash
git clone https://github.com/ahdave1573-dev/All-In-One-Bazaar.git
```

### 📂 Step 2: Move to Server Directory

Copy the cloned folder into your server's root directory:

- **XAMPP (Windows):** `C:/xampp/htdocs/All-In-One-Bazaar`
- **XAMPP (Mac):** `/Applications/XAMPP/htdocs/All-In-One-Bazaar`
- **Linux:** `/var/www/html/All-In-One-Bazaar`

### 🗄️ Step 3: Setup Database

1. Open **phpMyAdmin** → `http://localhost/phpmyadmin`
2. Create a new database named `allinonebazaar`
3. Import the SQL file:
   - Go to **Import** tab
   - Select `database/allinonebazaar.sql`
   - Click **Go**

### 🔗 Step 4: Configure Database Connection

Open `includes/db.php` and update the credentials:

```php
<?php
$host     = "localhost";
$username = "root";         // Your MySQL username
$password = "";             // Your MySQL password
$database = "allinonebazaar";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
```

### 🚀 Step 5: Run the Project

1. Start **Apache** and **MySQL** from XAMPP Control Panel
2. Open your browser and go to:

```
http://localhost/All-In-One-Bazaar/
```

---

## 🔐 Default Admin Login

> ⚠️ **Change these credentials after first login!**

```
URL:      http://localhost/All-In-One-Bazaar/admin/
Email:    admin@allinonebazaar.com
Password: admin123
```

---

## 🚀 Deployment (InfinityFree Hosting)

This project is deployed on **InfinityFree** free hosting:

1. Upload all files via **File Manager** or **FTP (FileZilla)**
2. Create MySQL database from **InfinityFree Control Panel**
3. Import the SQL file
4. Update `includes/db.php` with InfinityFree database credentials
5. Access your website at your domain

---

## 🤝 Contributing

Contributions are welcome! Here's how:

```bash
# 1. Fork the repository
# 2. Create your feature branch
git checkout -b feature/YourFeatureName

# 3. Commit your changes
git commit -m "Add: YourFeatureName"

# 4. Push to the branch
git push origin feature/YourFeatureName

# 5. Open a Pull Request
```

---

## 🐛 Known Issues / Future Improvements

- [ ] Add Payment Gateway integration (Razorpay / PayPal)
- [ ] Add Product Review & Rating System
- [ ] Email Notifications for Orders
- [ ] Wishlist Feature
- [ ] Coupon / Discount Code System
- [ ] Multi-language Support (Gujarati / Hindi / English)
- [ ] PWA (Progressive Web App) Support

---

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

---

## 👨‍💻 Developer

<div align="center">

| | | 
|---|---|
| **Name** | Anshul Dave |
| **Email** | [ahdave1573@gmail.com](mailto:ahdave1573@gmail.com) |
| **GitHub** | [@ahdave1573-dev](https://github.com/ahdave1573-dev) |
| **Website** | [allinonebazaar.infinityfreeapp.com](http://allinonebazaar.infinityfreeapp.com) |

</div>

---

<div align="center">

Made with ❤️ by **Anshul Dave**

</div>
