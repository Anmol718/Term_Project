# SecondHand Market

A full-stack web application for buying and selling second-hand items such as electronics, books, clothing, music, and collectibles.

---

## Student Information

| Field        | Details                  |
|-------------|--------------------------|
| **Name**     | Anmol Rehal              |
| **Course**   | Web Technologies         |
| **School**   | Algoma University        |

---

## Project Description

SecondHand Market is an online marketplace platform where users can register, log in, browse product listings, add items to a cart, and place orders. Sellers can list their own items for sale. An admin panel allows full management of products, orders, and users.

### Features
- User registration and login/logout
- Browse products by category, condition, and search
- Product detail pages
- Add to cart, update quantity, remove items
- Checkout and place orders
- Order history
- Sell an item (any logged-in user)
- Edit and delete own listings
- Admin dashboard with stats
- Admin manage all products, orders, and users

### Technologies Used
- **HTML5** – Page structure
- **CSS / Bootstrap 5** – Styling and responsive layout
- **JavaScript** – Client-side validation
- **PHP** – Server-side logic
- **MySQL** – Database
- **XAMPP** – Local development environment

---

## Database Schema

| Table         | Description                              |
|--------------|------------------------------------------|
| `users`       | Registered users and admin accounts     |
| `products`    | Item listings                            |
| `cart`        | Items added to a user's cart            |
| `orders`      | Placed orders                            |
| `order_items` | Individual products within each order   |

---

## Setup Instructions

### Requirements
- XAMPP (Apache + MySQL + PHP)
- A web browser

### Steps

**1. Clone the repository**
```bash
git clone https://github.com/<your-username>/TermProject.git
```
Place the folder inside your XAMPP `htdocs/` directory.

**2. Import the database**
- Start XAMPP and open phpMyAdmin: `http://localhost/phpmyadmin`
- Click **Import**
- Select `db/marketplace.sql`
- Click **Go**

**3. Start XAMPP**
- Start **Apache** and **MySQL** from the XAMPP control panel

**4. Visit the site**
```
http://localhost/TermProject/
```

---

## Admin Access

| Field    | Value                    |
|---------|--------------------------|
| Email    | admin@marketplace.com    |
| Password | admin123                 |

---

## Project Structure

```
TermProject/
├── admin/              # Admin panel pages
├── css/                # Stylesheet
├── db/                 # Database connection and SQL file
├── includes/           # Shared header, footer, config, auth
├── js/                 # JavaScript files
├── uploads/products/   # Uploaded product images
├── index.php           # Homepage
├── products.php        # Browse listings
├── product.php         # Product detail
├── cart.php            # Shopping cart
├── checkout.php        # Checkout
├── orders.php          # Order history
├── sell.php            # List an item
└── README.md
```
