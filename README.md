# NFT Marketplace App

A robust NFT marketplace developed using PHP, MySQL, and Bootstrap. Tailored for artists and collectors, it enables artwork publishing, browsing, and digital asset trading. The system includes secure authentication, role-based dashboards, artwork management, and a responsive user interface.

---

## Features

### Core Functionality

- **User Authentication** – Secure login system with session management
- **Role-Based Access** – Separate dashboards for Admin and User roles
- **NFT Management** – Users can upload, list, and manage NFTs with images
- **Artist Management** – Admins can add, edit, and delete artist profiles
- **Simulated Purchase System** – Demo payment modal for artwork purchases
- **Pagination** – Paginated views for browsing NFTs

### Technical Features

- **MVC Architecture** – Clean separation of Controllers, Views, and Models
- **Database Security** – Uses prepared statements to guard against SQL injection
- **Responsive UI** – Mobile-friendly design powered by Bootstrap
- **File Uploads** – Supports secure artwork image uploads
- **Session Management** – Ensures proper login sessions and access control

---

## Prerequisites

- XAMPP (Apache, MySQL, PHP)
- PHP 7.4 or newer
- MySQL 5.7 or newer
- Composer (for managing dependencies)

---

## Installation Guide

### Clone the Repository

```bash
git clone https://github.com/dishanta-adhikari/nft-marketplace-app.git
cd nft-marketplace-app
```

### Move to XAMPP Directory

Place the project folder inside the `htdocs` directory:

```
C:/xampp/htdocs/
```

### Database Setup

- Open phpMyAdmin
- Create a database (e.g., `nft_marketplace_db`)
- Import the provided SQL file (if available)

### Environment Configuration

Update the following values in `Config/Config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'nft_marketplace_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('APP_URL', 'http://localhost/nft-marketplace-app');
```

### Install Dependencies (If Using Composer)

```bash
composer install
```

### Run the App

Open in your browser:

```
http://localhost/nft-marketplace-app
```

---

## User Roles

### Admin

- Manage artists
- View and manage all NFTs
- Simulated payments overview

### User

- Upload and list NFT artworks
- Browse and purchase NFTs
- View personal listings

---

## Security Highlights

- **Prepared Statements** to prevent SQL Injection
- **File Validation** for secure uploads
- **Session-Based Authentication**
- **Input Validation** (Client & Server-side)
- **XSS Protection** through output escaping

---

## License

This project is for **educational/demo purposes** and is not intended for production use.

---

## Credits

Made with ❤️ using PHP.
