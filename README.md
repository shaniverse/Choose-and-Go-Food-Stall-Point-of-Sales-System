# CHOOSE AND GO - Food Stall POS System

A Point of Sale (POS) Web Application tailored for a food stall business. It allows customers to place and track their food orders and enables employees to manage menu items, orders, and users.

## 🚀 Features

### Customer Features
- Browse menu items by category (Meals, Snacks, Drinks)
- Search functionality
- Add items to cart with quantity and special instructions
- Place orders with cash or GCash payment options
- View order status and history

### Employee Features
- Secure login system with role-based access
- Product management (CRUD operations)
- Order management
- User management (admin only)
- Real-time order status updates

## 💻 Tech Stack

- PHP 7.4+
- MySQL 5.7+
- Bootstrap 5.3
- JavaScript (Vanilla)
- HTML5/CSS3

## 🛠️ Installation

1. Prerequisites:
   - XAMPP (with PHP 7.4+ and MySQL 5.7+)
   - Web browser (Chrome/Firefox recommended)

2. Setup Steps:
   ```powershell
   # Clone the repository to your XAMPP htdocs folder
   cd c:\xampp\htdocs
   git clone <repository-url> "IM FINAL FOODSTALL SYSTEM"

   # Start XAMPP services
   # Start Apache and MySQL from XAMPP Control Panel

   # Open your web browser and visit:
   # http://localhost/IM FINAL FOODSTALL SYSTEM/setup.php
   ```

3. Test Accounts:
   - Admin: ID: 1, Password: test123
   - Cashier: ID: 2, Password: test123
   - Kitchen Staff: ID: 3, Password: test123

## 🎨 Color Palette

- Primary Colors:
  - Warm Red: `#D43F3F`
  - Off-White/Cream: `#F5F0E7`
  
- Secondary Colors:
  - Dark Grey/Charcoal: `#333333`
  - Golden Yellow: `#F1C40F`
  - Olive Green: `#628C69`
  
- Accent Colors:
  - Soft Pink/Peach: `#E8B9A6`
  - Light Blue: `#A3D2F0`

## 📁 Project Structure

```
choose-and-go-foodstall/
├── assets/
│   ├── css/          # Stylesheets
│   ├── js/           # JavaScript files
│   └── images/       # Image assets
├── includes/
│   ├── db.php        # Database connection
│   └── functions.php # Helper functions
├── modules/
│   ├── products/     # Product management
│   ├── orders/       # Order management
│   └── users/        # User management
├── views/            # Reusable view components
└── login/            # Authentication
```

## 🔒 Security Features

- Password hashing using PHP's password_hash()
- Session-based authentication
- Role-based access control
- SQL injection prevention using PDO
- XSS prevention using sanitization
- CSRF protection
- Input validation (server-side and client-side)

## 💡 Usage Guidelines

1. Customer Interface:
   - Browse menu items
   - Add items to cart
   - Provide contact details
   - Choose payment method
   - Place order

2. Employee Interface:
   - Login with provided credentials
   - Manage assigned tasks based on role
   - Update order statuses
   - Handle product inventory

## ⚠️ Important Notes

1. Default image path for products: `images/CHOOSE AND GO LOGO.jpg`
2. Supported payment methods: Cash and GCash
3. Order statuses: Pending, Preparing, Ready, Completed, Cancelled
4. Employee roles: Admin, Cashier, Kitchen Staff

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Open a pull request

## 📝 License

This project is licensed under the MIT License.
