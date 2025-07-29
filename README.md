# NFT Marketplace App

A robust NFT marketplace developed using PHP, MySQL, and Bootstrap. Tailored for digital artists and collectors, it streamlines the process of creating, showcasing, and purchasing NFT artworks. The system includes secure authentication, role-based dashboards, artwork management, simulated purchases, and a responsive user interface.

---

## Features

### Core Functionality

- **User Authentication** – Secure login system with session management
- **Role-Based Access** – Separate dashboards for Admin and User roles
- **NFT Management** – Users can create, edit, and delete their NFT listings
- **Artist Management** – Admins can manage artist profiles
- **Simulated Purchase System** – Allows users to simulate artwork purchases
- **Duplicate Purchase Prevention** – Prevents buying the same artwork multiple times

### Technical Features

- **MVC Architecture** – Clean separation of Controllers, Views, and Models
- **Database Security** – Uses prepared statements to guard against SQL injection
- **Responsive UI** – Mobile-friendly design powered by Bootstrap
- **File Uploads** – Supports secure image uploads for artworks
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
- Import the provided SQL file
- Update the database credentials in `Config/Config.php`

### Environment Configuration

```bash
cp .env.example .env
```

Edit `.env` with:

```env
DB_HOST=localhost
DB_NAME=nft_marketplace_db
DB_USER=root
DB_PASS=
APP_URL=http://localhost/nft-marketplace-app
```

### Install Dependencies

```bash
composer install
```

---

## Run the App

Open in your browser:

```
http://localhost/nft-marketplace-app
```

---

## User Roles

### Admin

- Manage artist accounts
- View and moderate all NFT listings
- Monitor purchase simulations
- Manage platform-wide content

### User

- Browse NFT artworks
- Create and manage own NFT listings
- Simulate artwork purchases
- Manage personal profile

---

## Security Highlights

- **Prepared Statements** to prevent SQL Injection
- **File Validation** for secure image uploads
- **Session-Based Authentication**
- **Input Validation** (Client & Server-side)
- **XSS Protection** through output escaping

---

## License

This project is open-source/educational/demo purposes and available under the MIT License.

---

**Made with ❤️ to empower digital creators and collectors.**
