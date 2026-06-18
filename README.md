[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-22041afd0340ce965d47ae6ef1cefeee28c7c493a6346c4f15d667ab976d596c.svg)](https://classroom.github.com/a/OFWe9D1G)
A complete PHP-based e-commerce platform for sustainable second-hand clothing with admin dashboard, user management, and shopping cart functionality.

📋 Table of Contents
About The Project

Features

Technology Stack

System Requirements

Installation Guide

Database Setup

Default Login Credentials

Project Structure

User Guide

Admin Guide

Troubleshooting

Contributing

License

Contact

🎯 About The Project
Past Times is a sustainable second-hand clothing store web application built with PHP and MySQL. It allows customers to browse, select, and purchase pre-loved clothing items while promoting sustainability and reducing fashion waste.

The platform includes:

User registration and authentication

Shopping cart functionality

Admin dashboard for management

Seller communication system

Inventory management

✨ Features
👤 Customer Features
User Registration & Login - Secure account creation and authentication

Product Browsing - View all available clothing items with filtering options

Shopping Cart - Add items, update quantities, and remove items

Checkout Process - Proceed to checkout and continue shopping

User Profiles - Manage personal information

Order Tracking - View order status and history

👔 Admin Features
Admin Dashboard - Overview of all users and system statistics

User Management - View, verify, update, and delete user accounts

Inventory Management - Add, update, and delete clothing items

Seller Communication - Send messages to buyers and sellers

Account Verification - Verify new user accounts

Role Management - Assign admin or customer roles

🌱 Sustainability Features
Eco-Friendly Focus - Promoting sustainable fashion choices

Quality Control - Verification system for product quality

Community Building - Connecting buyers with sellers

🛠️ Technology Stack
Component	Technology
Backend	PHP 7.4+
Database	MySQL 5.7+ / MariaDB 10.2+
Frontend	HTML5, CSS3, JavaScript
Server	Apache (XAMPP / WAMP / MAMP)
Fonts	Google Fonts (Playfair Display, Montserrat)
Icons	Unicode Emojis
💻 System Requirements
Minimum Requirements:
Web Server: Apache 2.4+

PHP: Version 7.4 or higher

MySQL: Version 5.7 or higher

Browser: Modern browser (Chrome, Firefox, Edge, Safari)

Disk Space: 50MB minimum

Recommended:
XAMPP v7.4+ or WAMP v3.0+ or MAMP v5.0+

PHP 8.0+

MySQL 8.0+

RAM: 2GB minimum

📥 Installation Guide
Step 1: Download and Install XAMPP/WAMP/MAMP
Option A: XAMPP (Recommended)
Download XAMPP from: https://www.apachefriends.org/

Install XAMPP on your system

Start Apache and MySQL services from the XAMPP Control Panel

Option B: WAMP
Download WAMP from: http://www.wampserver.com/

Install WAMP on your system

Start WAMP server

Option C: MAMP
Download MAMP from: https://www.mamp.info/

Install MAMP on your system

Start MAMP server

Step 2: Download the Project Files
Download the project ZIP file or clone the repository

Extract the files to your web server's document root:

XAMPP: C:\xampp\htdocs\ClothingStore\

WAMP: C:\wamp64\www\ClothingStore\

MAMP: C:\MAMP\htdocs\ClothingStore\

Step 3: Set Up the Database
Open your browser and go to: http://localhost/phpmyadmin

Click on "New" in the left sidebar

Create a database named: clothingstore

Click on the "Import" tab

Choose the database.sql file from the project folder

Click "Go" to import the database

Alternative Database Setup:
Copy the database.sql file to your project root

Run the install.php file in your browser: http://localhost/ClothingStore/install.php

Follow the on-screen instructions

Step 4: Configure Database Connection
The database connection is already configured in DBConn.php:

php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clothingstore";
Note: If you have a MySQL password set, update the $pass variable accordingly.

Step 5: Test the Application
Open your browser

Navigate to: http://localhost/ClothingStore/

You should see the home page of Past Times

🗄️ Database Setup
Option 1: Using phpMyAdmin (Easiest)
Go to: http://localhost/phpmyadmin

Create database: clothingstore

Import database.sql

Option 2: Using Command Line
bash
mysql -u root -p < database.sql
Option 3: Using the Install Script
Create a file install.php in the project root

Add the following code:

php
<?php
include 'DBConn.php';
$sql = file_get_contents('database.sql');
if (mysqli_multi_query($conn, $sql)) {
    echo "✅ Database setup complete!";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}
?>
Run: http://localhost/ClothingStore/install.php

🔑 Default Login Credentials
Admin Access:
Field	Value
Email	admin@clothingstore.co.za
Password	Admin123
Customer Access:
Email	Password	Status
lizzy.m@clothingstore.co.za	Customer123	Verified
mulwali@clothingstore.co.za	Customer123	Verified
bridgette@clothingstore.co.za	Customer123	Pending
shakes@clothingstore.co.za	Customer123	Verified
given@clothingstore.co.za	Customer123	Pending
📁 Project Structure
text
ClothingStore/
│
├── 📄 README.md              # Project documentation
├── 📄 database.sql           # Complete database script
├── 📄 install.php            # Database installation script
│
├── 📄 index.php              # Home page
├── 📄 shop.php               # Product listing page
├── 📄 cart.php               # Shopping cart
├── 📄 login.php              # User login
├── 📄 register.php           # User registration
├── 📄 logout.php             # Logout handler
├── 📄 DBConn.php             # Database connection
│
├── 📄 adminLogin.php         # Admin login
├── 📄 adminDashboard.php     # Admin dashboard
├── 📄 submit_clothing.php    # Inventory management
├── 📄 updateCustomer.php     # Edit customer
├── 📄 createTable.php        # Table creation script
│
├── 📄 about.html             # About page
├── 📄 team.html              # Team page
│
├── 📄 userData.txt           # Sample user data
├── 📄 DocumentLayout.json    # IDE configuration
├── 📄 phptasks.json          # PHP tasks config
├── 📄 VSWorkspaceState.json  # Workspace state
│
└── 📁 uploads/               # Uploaded images (auto-created)
    └── (image files)
👤 User Guide
Customer Registration
Navigate to register.php

Fill in your details:

Full Name

Email Address

Password (minimum 6 characters)

Confirm Password

Click "Register"

Wait for admin verification (status will be "pending")

Customer Login
Navigate to login.php

Enter your registered email and password

Click "Login"

You'll be redirected to the shop page

Browsing Products
Navigate to shop.php

Browse available items

Use category filters (Women's, Men's, Accessories, On Sale)

Click "Add to Cart" on any item

Shopping Cart
View your cart by clicking the cart icon (top-right)

Update quantities using the input field and "Update" button

Remove items using the "Remove" button

Click "Continue Shopping" to return to products

Click "Proceed to Checkout" to complete your order

Logout
Click the "Logout" button in the navigation bar

You'll be redirected to the home page

👔 Admin Guide
Admin Login
Navigate to adminLogin.php or click "Admin Portal" on the home page

Enter admin credentials:

Email: admin@clothingstore.co.za

Password: Admin123

Click "Login"

Admin Dashboard
The dashboard provides an overview of:

Total Users

Pending Verifications

Verified Users

Admin Accounts

User Management
Verify Users: Click the "Verify" button next to pending users

Update Users: Click the "Update" button to edit user details

Delete Users: Click the "Delete" button (cannot delete your own account)

Inventory Management
Go to "Manage Inventory" from the admin dashboard

Add New Items:

Fill in brand, description, price

Upload an image

Click "Publish Inventory Item"

Delete Users:

Enter the target user ID

Click "Purge Account Record"

Communication
Go to "Manage Inventory"

Under "Quality Control Communication Desk":

Enter target user ID

Type your message

Click "Dispatch Message"

🔧 Troubleshooting
Common Issues and Solutions
Issue: Database Connection Error
Solution:

Check if MySQL service is running

Verify database credentials in DBConn.php

Make sure database clothingstore exists

Issue: Admin Login Not Working
Solution:

Run createTable.php to create admin user

Check database for admin user: SELECT * FROM Customer WHERE role = 'admin'

Reset password in database:

sql
UPDATE Customer SET password_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE email = 'admin@clothingstore.co.za';
Issue: Cart Items Not Saving
Solution:

Check if PHP sessions are enabled

Clear browser cookies and cache

Check PHP error logs

Issue: Images Not Displaying
Solution:

Check the uploads/ folder exists and is writable

Verify image URLs in the database

Check file permissions (755 for folders, 644 for files)

Issue: 404 Page Not Found
Solution:

Make sure you're using the correct URL

Check if all files are in the correct directory

Verify file names (case sensitive on Linux)

Issue: PHP Error Display
Solution:

Enable error reporting in php.ini:

ini
display_errors = On
error_reporting = E_ALL
Check Apache error logs: C:\xampp\apache\logs\error.log

Check PHP error logs: C:\xampp\php\logs\php_error_log

🤝 Contributing
We welcome contributions to improve Past Times! Here's how:

Fork the Repository

Create a Feature Branch

bash
git checkout -b feature/AmazingFeature
Commit Your Changes

bash
git commit -m 'Add some AmazingFeature'
Push to the Branch

bash
git push origin feature/AmazingFeature
Open a Pull Request

Coding Standards
Use PHP 7.4+ features

Follow PSR-12 coding standards

Comment your code thoroughly

Use meaningful variable names

Sanitize all user inputs

📄 License
This project is licensed under the MIT License - see below:

text
MIT License

Copyright (c) 2026 Past Times Clothing Store

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
📞 Contact
Development Team
Project Lead: [Your Name]

Email: [your.email@example.com]

Support
For technical support or queries:

Email: hello@pasttimes.com

Phone: (555) 123-4567

Location: Cape Town, South Africa

Social Media
📷 Instagram: @pasttimes

👍 Facebook: /pasttimes

🐦 Twitter: @pasttimes

🎵 TikTok: @pasttimes

🌟 Acknowledgments
Unsplash for the beautiful product images

Google Fonts for typography

XAMPP for the development environment

All contributors who helped make this project possible

🔄 Version History
v1.0.0 (June 2026)
✅ Initial release

✅ User authentication system

✅ Shopping cart functionality

✅ Admin dashboard

✅ Inventory management

✅ Communication system

✅ Sustainable fashion focus

📊 Project Status
Feature	Status
User Registration	✅ Complete
User Login	✅ Complete
Product Browsing	✅ Complete
Shopping Cart	✅ Complete
Cart Management	✅ Complete
Admin Dashboard	✅ Complete
User Management	✅ Complete
Inventory Management	✅ Complete
Communication System	✅ Complete
Responsive Design	✅ Complete
🚀 Future Enhancements
Payment gateway integration

Order history and tracking

Email notifications

Product reviews and ratings

Wishlist functionality

Advanced search and filters

Mobile app development

Multi-language support

Social media login

Analytics dashboard

📝 Quick Start Commands
bash
# Clone the repository
git clone https://github.com/yourusername/ClothingStore.git

# Navigate to project directory
cd ClothingStore

# Import database (using MySQL)
mysql -u root -p clothingstore < database.sql

# Start development server (PHP built-in)
php -S localhost:8000

# Or use XAMPP/WAMP/MAMP
# Copy files to htdocs/www folder
# Access http://localhost/ClothingStore/
⚡ Performance Optimization Tips
Enable PHP OpCache for faster execution

Use a CDN for images and assets

Implement caching for frequently accessed data

Optimize database queries with proper indexing

Minify CSS and JavaScript files

Enable Gzip compression on the server
