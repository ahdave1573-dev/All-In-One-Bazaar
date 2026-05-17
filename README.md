# 🛒 All-In-One Bazaar

All-In-One Bazaar is a comprehensive, multi-category e-commerce web application built using PHP and MySQL. Designed as a modern digital marketplace, it provides users with a seamless and visually appealing shopping experience with a stunning 3D glassmorphism UI.

![All-In-One Bazaar](https://img.shields.io/badge/version-1.0.0-blue.svg)
![PHP](https://img.shields.io/badge/PHP-7.4+-purple.svg)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

## 🌟 Live Demo

**Repository:** [https://github.com/ahdave1573-dev/All-In-One-Bazaar.git](https://github.com/ahdave1573-dev/All-In-One-Bazaar.git)

## ✨ Features

### 👥 User Features
- **🔐 Secure Authentication:** User registration, login, and session management
- **📦 Product Catalog:** Browse unlimited products across multiple categories
- **🔍 Advanced Search:** Search products by name, category, or price range
- **🛍️ Shopping Cart:** Add/remove items, update quantities, and save for later
- **💳 Secure Checkout:** Safe and intuitive checkout process
- **📋 Order Management:** Track orders, view history, and download invoices
- **🎁 Special Offers:** Exclusive deals and discount sections
- **👤 Profile Management:** Edit personal information and preferences
- **⭐ Product Reviews:** Rate and review purchased products
- **📱 Responsive Design:** Seamless experience across all devices

### 🔧 Admin Features
- **📊 Comprehensive Dashboard:** Overview of sales, orders, and users
- **📦 Product Management:** 
  - Add, edit, and delete products
  - Manage categories and subcategories
  - Upload and manage product images
  - Set pricing and inventory levels
- **👥 User Management:** View and manage registered customers
- **📈 Order Processing:** Track, update, and manage customer orders
- **💰 Sales Analytics:** Revenue reports and statistics
- **⚙️ Store Settings:** Configure site settings and preferences
- **🖼️ Media Library:** Manage all uploaded images and files

## 🎨 Design Highlights

- ✨ Modern 3D glassmorphism UI effects
- 📱 Fully responsive design for mobile, tablet, and desktop
- 🎯 Intuitive user interface with smooth navigation
- 🌈 Eye-catching product displays and galleries
- ⚡ Fast loading times with optimized assets
- 🎭 Professional animations and transitions
- 🎨 Clean and organized layout structure

## 🛠️ Technology Stack

| Technology | Purpose |
|------------|---------|
| **PHP 7.4+** | Server-side scripting |
| **MySQL** | Database management |
| **HTML5** | Page structure |
| **CSS3** | Styling and animations |
| **JavaScript** | Client-side interactivity |
| **AJAX** | Asynchronous requests |
| **Apache** | Web server |

## 📋 Prerequisites

Before installation, ensure you have:

- ✅ XAMPP, WAMP, or LAMP (local server)
- ✅ PHP version 7.4 or higher
- ✅ MySQL 5.7 or higher
- ✅ Apache web server
- ✅ Modern web browser
- ✅ Text editor (VS Code recommended)

## 🚀 Quick Installation

### Method 1: Using Git Clone

```bash
# Clone the repository
git clone https://github.com/ahdave1573-dev/All-In-One-Bazaar.git

# Navigate to your web server directory
cd C:\xampp\htdocs  # For Windows XAMPP
# or
cd /Applications/XAMPP/htdocs  # For Mac XAMPP

# Move/Copy the project
mv All-In-One-Bazaar DD
```

### Method 2: Manual Download

1. Download ZIP from: [https://github.com/ahdave1573-dev/All-In-One-Bazaar/archive/refs/heads/main.zip](https://github.com/ahdave1573-dev/All-In-One-Bazaar/archive/refs/heads/main.zip)
2. Extract to your web server directory
3. Rename folder to `DD` (or your preferred name)

## 📦 Detailed Installation Steps

### Step 1: Place Project Files

**For XAMPP (Windows):**
```
C:\xampp\htdocs\DD
```

**For XAMPP (Mac):**
```
/Applications/XAMPP/htdocs/DD
```

**For WAMP:**
```
C:\wamp\www\DD
```

**For LAMP (Linux):**
```
/var/www/html/DD
```

### Step 2: Database Setup

1. **Start your server** (Apache + MySQL)

2. **Open phpMyAdmin:**
   ```
   http://localhost/phpmyadmin
   ```

3. **Create Database:**
   - Click on "New" in the left sidebar
   - Database name: `digitalbazaar` (or `allinonebazaar`)
   - Collation: `utf8mb4_general_ci`
   - Click "Create"

4. **Import Database:**
   - Select your newly created database
   - Click "Import" tab
   - Choose file: `digitalbazaar.sql` from project root
   - Click "Go" to import

### Step 3: Configure Database Connection

Edit `db.php` in the project root:

```php
<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";  // Default is empty for XAMPP
$database = "digitalbazaar";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");
?>
```

### Step 4: Set Permissions (Linux/Mac only)

```bash
# Navigate to project directory
cd /path/to/DD

# Set folder permissions
chmod 755 assets/images/products
chmod 755 assets/images/users
chmod 755 assets/images/banners

# If needed, set recursive permissions
chmod -R 755 assets/images
```

### Step 5: Access the Application

**Frontend (User Interface):**
```
http://localhost/DD
```

**Admin Panel:**
```
http://localhost/DD/admin
```

## 🔑 Default Login Credentials

### 🔐 Admin Access
```
URL: http://localhost/DD/admin
Username: admin
Password: admin123
```

### 👤 Test User Account
```
Email: user@example.com
Password: user123
```

> ⚠️ **Important:** Change these credentials immediately after first login!

## 📁 Project Structure

```
DD/
│
├── admin/                      # Admin Panel
│   ├── index.php              # Dashboard
│   ├── products.php           # Product management
│   ├── add_product.php        # Add new product
│   ├── edit_product.php       # Edit product
│   ├── orders.php             # Order management
│   ├── users.php              # User management
│   ├── categories.php         # Category management
│   ├── settings.php           # Store settings
│   └── logout.php             # Admin logout
│
├── assets/                     # Static Resources
│   ├── css/
│   │   ├── style.css          # Main stylesheet
│   │   ├── admin.css          # Admin styles
│   │   ├── responsive.css     # Responsive design
│   │   └── glassmorphism.css  # 3D effects
│   ├── js/
│   │   ├── main.js            # Main JavaScript
│   │   ├── cart.js            # Cart functionality
│   │   ├── product.js         # Product interactions
│   │   └── admin.js           # Admin scripts
│   └── images/
│       ├── products/          # Product images
│       ├── users/             # User avatars
│       ├── banners/           # Banner images
│       └── icons/             # Site icons
│
├── ajax/                       # AJAX Handlers
│   ├── add_to_cart.php        # Add to cart
│   ├── update_cart.php        # Update cart
│   ├── remove_from_cart.php   # Remove item
│   ├── search.php             # Search products
│   └── filter.php             # Filter products
│
├── config/                     # Configuration
│   ├── config.php             # Site config
│   ├── constants.php          # Constants
│   └── database.php           # DB config
│
├── includes/                   # Reusable Components
│   ├── header.php             # Site header
│   ├── footer.php             # Site footer
│   ├── navbar.php             # Navigation
│   ├── sidebar.php            # Sidebar
│   └── functions.php          # Helper functions
│
├── uploads/                    # User Uploads
│   ├── products/              # Product images
│   └── users/                 # User images
│
├── index.php                   # Homepage
├── products.php                # Product listing
├── product_details.php         # Product detail page
├── cart.php                    # Shopping cart
├── checkout.php                # Checkout page
├── my_orders.php               # Order history
├── order_details.php           # Single order view
├── invoice.php                 # Invoice generation
├── login.php                   # User login
├── register.php                # User registration
├── logout.php                  # Logout
├── edit-profile.php            # Profile editor
├── offers.php                  # Special offers
├── offer_details.php           # Offer details
├── search.php                  # Search results
├── contact.php                 # Contact page
├── about.php                   # About page
├── terms.php                   # Terms & conditions
├── privacy.php                 # Privacy policy
├── db.php                      # Database connection
├── digitalbazaar.sql           # Database dump
├── .htaccess                   # Apache config
└── README.md                   # Documentation
```

## ⚙️ Configuration

### Site Settings

Edit `config/config.php`:

```php
<?php
// Site Information
define('SITE_NAME', 'All-In-One Bazaar');
define('SITE_URL', 'http://localhost/DD');
define('SITE_EMAIL', 'ahdave1573@gmail.com');

// Admin Settings
define('ADMIN_EMAIL', 'ahdave1573@gmail.com');
define('ITEMS_PER_PAGE', 12);

// Upload Settings
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);

// Currency
define('CURRENCY', '₹');
define('CURRENCY_CODE', 'INR');
?>
```

### Database Configuration

Edit `db.php`:

```php
<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "digitalbazaar";
?>
```

### PHP Settings

Update your `php.ini` file:

```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
memory_limit = 256M
session.gc_maxlifetime = 1440
```

## 🎯 How to Use

### For Customers:

1. **Register/Login**
   - Click on "Register" to create an account
   - Or login with existing credentials

2. **Browse Products**
   - View all products on homepage
   - Use categories to filter
   - Search for specific items

3. **Add to Cart**
   - Click "Add to Cart" on any product
   - Adjust quantities as needed
   - View cart anytime

4. **Checkout**
   - Review cart items
   - Enter shipping details
   - Place order

5. **Track Orders**
   - Go to "My Orders"
   - View order status
   - Download invoice

### For Administrators:

1. **Login to Admin Panel**
   - Navigate to `/admin`
   - Enter admin credentials

2. **Manage Products**
   - Add new products with images
   - Edit existing products
   - Delete discontinued items
   - Manage categories

3. **Process Orders**
   - View all orders
   - Update order status
   - Print invoices

4. **Manage Users**
   - View registered users
   - Edit user details
   - Manage permissions

5. **View Analytics**
   - Check sales reports
   - Monitor inventory
   - Track revenue

## 🐛 Troubleshooting

### Common Issues & Solutions

#### 1. **Database Connection Error**

**Error:** `Connection failed: Access denied`

**Solutions:**
```php
// Check db.php credentials
$username = "root";  // Correct username
$password = "";      // Usually empty for XAMPP

// Test MySQL is running
// Open XAMPP Control Panel
// Start MySQL if stopped
```

#### 2. **404 Page Not Found**

**Solutions:**
- Verify Apache is running
- Check project folder name matches URL
- Verify `.htaccess` exists
- Clear browser cache

#### 3. **Images Not Displaying**

**Solutions:**
```bash
# Check permissions (Linux/Mac)
chmod 755 assets/images
chmod 755 uploads

# Verify image paths in database
# Check if images exist in folder
```

#### 4. **Blank White Page**

**Solutions:**
```php
// Enable error reporting
// Add to top of index.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check error logs
// XAMPP: C:\xampp\apache\logs\error.log
```

#### 5. **Upload Failed**

**Solutions:**
```ini
# Check php.ini
upload_max_filesize = 10M
post_max_size = 10M

# Verify folder permissions
# Check file extension is allowed
```

#### 6. **Session Issues**

**Solutions:**
```php
// Ensure session_start() is called
// Check session save path is writable
// Clear browser cookies
// Check PHP session settings
```

## 🔒 Security Best Practices

Before going live, implement these security measures:

### 1. **Change Default Credentials**
```sql
-- Update admin password
UPDATE users SET password = MD5('new_secure_password') WHERE role = 'admin';
```

### 2. **Use Prepared Statements**
```php
// Already implemented in the project
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
```

### 3. **Validate User Input**
```php
// Sanitize inputs
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$name = htmlspecialchars($_POST['name']);
```

### 4. **Secure File Uploads**
```php
// Validate file type and size
$allowed = ['jpg', 'jpeg', 'png', 'gif'];
$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
if (!in_array($ext, $allowed)) {
    die("Invalid file type");
}
```

### 5. **Enable HTTPS**
- Get SSL certificate
- Update .htaccess for HTTPS redirect

### 6. **Hide Errors in Production**
```php
// In production
ini_set('display_errors', 0);
error_reporting(0);
```

### 7. **Regular Backups**
```bash
# Backup database
mysqldump -u root -p digitalbazaar > backup.sql

# Backup files
tar -czf backup.tar.gz /path/to/DD
```

## 📱 Browser Compatibility

| Browser | Supported Version |
|---------|------------------|
| Chrome | ✅ Latest |
| Firefox | ✅ Latest |
| Safari | ✅ Latest |
| Edge | ✅ Latest |
| Opera | ✅ Latest |
| IE 11 | ⚠️ Partial |

## 🚀 Performance Optimization

### Image Optimization
```php
// Compress images before upload
// Use WebP format
// Lazy load images
```

### Database Optimization
```sql
-- Add indexes
CREATE INDEX idx_product_category ON products(category_id);
CREATE INDEX idx_order_user ON orders(user_id);
```

### Caching
```apache
# Enable browser caching in .htaccess
<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType image/jpg "access 1 year"
  ExpiresByType image/jpeg "access 1 year"
  ExpiresByType image/png "access 1 year"
  ExpiresByType text/css "access 1 month"
  ExpiresByType application/javascript "access 1 month"
</IfModule>
```

## 🎓 Learning Resources

- [PHP Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [MDN Web Docs](https://developer.mozilla.org/)
- [W3Schools](https://www.w3schools.com/)
- [Stack Overflow](https://stackoverflow.com/)

## 🔄 Future Enhancements

- [ ] Payment Gateway Integration (Razorpay, PayPal, Stripe)
- [ ] Email Notifications (Order confirmation, shipping updates)
- [ ] SMS Notifications
- [ ] Wishlist Feature
- [ ] Product Comparison
- [ ] Advanced Filters (Price range, ratings, brand)
- [ ] Social Media Integration
- [ ] Multi-language Support
- [ ] Currency Converter
- [ ] Live Chat Support
- [ ] Mobile App (React Native/Flutter)
- [ ] Coupon & Discount System
- [ ] Loyalty Points Program
- [ ] Product Recommendations (AI-based)
- [ ] Advanced Analytics Dashboard
- [ ] Inventory Management System
- [ ] Vendor/Multi-seller Support
- [ ] Blog Section
- [ ] Newsletter System
- [ ] Review & Rating System Enhancement

## 🤝 Contributing

We welcome contributions! Here's how you can help:

### Steps to Contribute

1. **Fork the Repository**
   ```bash
   # Click 'Fork' button on GitHub
   ```

2. **Clone Your Fork**
   ```bash
   git clone https://github.com/YOUR-USERNAME/All-In-One-Bazaar.git
   cd All-In-One-Bazaar
   ```

3. **Create a Branch**
   ```bash
   git checkout -b feature/YourFeatureName
   # or
   git checkout -b fix/YourBugFix
   ```

4. **Make Changes**
   - Write clean code
   - Follow existing code style
   - Add comments where needed

5. **Test Your Changes**
   - Test thoroughly
   - Check for errors
   - Verify on different browsers

6. **Commit Changes**
   ```bash
   git add .
   git commit -m "Add: Your feature description"
   ```

7. **Push to GitHub**
   ```bash
   git push origin feature/YourFeatureName
   ```

8. **Create Pull Request**
   - Go to your fork on GitHub
   - Click "Pull Request"
   - Describe your changes

### Contribution Guidelines

- ✅ Follow PSR coding standards
- ✅ Write meaningful commit messages
- ✅ Test before submitting
- ✅ Update documentation
- ✅ Add comments to complex code
- ✅ Keep pull requests focused
- ✅ Respond to code review feedback

## 📄 License

This project is licensed under the **MIT License**.

```
MIT License

Copyright (c) 2024 All-In-One Bazaar

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

## 📧 Contact & Support

Need help? Reach out to us:

- **Developer:** Ashish Dave
- **Email:** [ahdave1573@gmail.com](mailto:ahdave1573@gmail.com)
- **GitHub:** [@ahdave1573-dev](https://github.com/ahdave1573-dev)
- **Repository:** [All-In-One-Bazaar](https://github.com/ahdave1573-dev/All-In-One-Bazaar)
- **Issues:** [Report Bug](https://github.com/ahdave1573-dev/All-In-One-Bazaar/issues)
- **Discussions:** [GitHub Discussions](https://github.com/ahdave1573-dev/All-In-One-Bazaar/discussions)

### Get in Touch

For any queries, support, or collaboration:
- 📧 Email: ahdave1573@gmail.com
- 🐛 Bug Reports: Use GitHub Issues
- 💡 Feature Requests: Use GitHub Discussions
- 🤝 Collaboration: Feel free to reach out!

## 🙏 Acknowledgments

- Thanks to all contributors who help improve this project
- Inspired by leading e-commerce platforms like Amazon, Flipkart, and eBay
- Built with ❤️ by Ashish Dave and the open-source community
- Special thanks to PHP & MySQL developers worldwide
- Icons from [Font Awesome](https://fontawesome.com/)
- UI/UX inspiration from modern design trends
- Testing support from the developer community

## 📊 Project Stats

![GitHub stars](https://img.shields.io/github/stars/ahdave1573-dev/All-In-One-Bazaar?style=social)
![GitHub forks](https://img.shields.io/github/forks/ahdave1573-dev/All-In-One-Bazaar?style=social)
![GitHub issues](https://img.shields.io/github/issues/ahdave1573-dev/All-In-One-Bazaar)
![GitHub pull requests](https://img.shields.io/github/issues-pr/ahdave1573-dev/All-In-One-Bazaar)
![GitHub last commit](https://img.shields.io/github/last-commit/ahdave1573-dev/All-In-One-Bazaar)
![GitHub repo size](https://img.shields.io/github/repo-size/ahdave1573-dev/All-In-One-Bazaar)

### Development Statistics

- **Total Files:** 50+
- **Lines of Code:** 10,000+
- **Database Tables:** 8+
- **API Endpoints:** 20+
- **Supported Products:** Unlimited
- **Admin Features:** 15+
- **User Features:** 25+
- **Languages Used:** PHP, JavaScript, HTML, CSS, SQL

## 📸 Screenshots

### Frontend

#### 🏠 Homepage
*Beautiful landing page with featured products and special offers*

#### 📦 Product Listing
*Clean product grid with advanced filters and search*

#### 🔍 Product Details
*Detailed product view with images, description, and reviews*

#### 🛒 Shopping Cart
*User-friendly cart interface with quantity management*

#### 💳 Checkout
*Smooth and secure checkout process*

#### 📱 Mobile View
*Fully responsive design for mobile devices*

### Backend

#### 📊 Admin Dashboard
*Comprehensive analytics and sales overview*

#### 📦 Product Management
*Easy product administration with bulk operations*

#### 📈 Order Management
*Efficient order processing and tracking*

#### 👥 User Management
*Customer database and user analytics*

---

## 🎯 Quick Start Guide

### For First-Time Users

1. **Download the project**
2. **Extract to htdocs**
3. **Import database**
4. **Configure db.php**
5. **Open in browser**
6. **Start shopping!**

### For Developers

1. **Clone repository**
2. **Setup local environment**
3. **Configure settings**
4. **Start developing**
5. **Submit pull requests**

---

## 🌟 Key Features at a Glance

✅ **User-Friendly Interface**  
✅ **Secure Shopping Cart**  
✅ **Order Tracking**  
✅ **Admin Dashboard**  
✅ **Responsive Design**  
✅ **Product Management**  
✅ **Multiple Payment Options**  
✅ **Search & Filter**  
✅ **Special Offers**  
✅ **Invoice Generation**  

---

## 💡 Tips & Tricks

### For Admins
- Regularly update product inventory
- Monitor sales analytics
- Respond to customer queries promptly
- Keep product images high-quality
- Run special promotions regularly

### For Developers
- Keep code clean and documented
- Test on multiple browsers
- Optimize database queries
- Use version control
- Follow security best practices

---

## 🚨 Important Notes

> ⚠️ **Security Warning:** This is a demonstration project. For production use, implement additional security measures including:
> - SSL/HTTPS encryption
> - Strong password policies
> - Two-factor authentication
> - Regular security audits
> - Data encryption
> - CSRF protection
> - XSS prevention

> 📝 **Note:** Default credentials are for testing only. Change them immediately in production.

> 💾 **Backup:** Always maintain regular backups of your database and files.

---

## ⭐ Support the Project

If you find this project helpful, please consider:

- ⭐ **Starring the repository** on GitHub
- 🍴 **Forking and contributing** improvements
- 🐛 **Reporting bugs** and issues
- 💡 **Suggesting features** and enhancements
- 📢 **Sharing with others** who might benefit
- 💬 **Providing feedback** to help us improve
- ☕ **Supporting the developer** (optional)

---

## 📚 Documentation

For detailed documentation, please visit:
- [Installation Guide](docs/installation.md)
- [User Manual](docs/user-guide.md)
- [Admin Guide](docs/admin-guide.md)
- [API Documentation](docs/api.md)
- [FAQ](docs/faq.md)

---

## 🎉 Thank You!

Thank you for choosing All-In-One Bazaar! We hope this project helps you build amazing e-commerce experiences.

**Happy Coding! Happy Selling! Happy Shopping!** 🛍️

---

**Made with ❤️ in India | Open Source Forever**

**Version 1.0.0** | **Last Updated:** December 2024

---

### 📞 Stay Connected

Follow us for updates and announcements:
- 🐙 GitHub: [@ahdave1573-dev](https://github.com/ahdave1573-dev)
- 📧 Email: ahdave1573@gmail.com

---

**© 2024 All-In-One Bazaar. All Rights Reserved.**
