# NFT Marketplace App

A web-based NFT marketplace built with PHP and MySQL, allowing users to buy, sell, and manage digital artworks as NFTs. The platform features user and admin dashboards, artwork management, payments (demo), and artist management.

## Features

- User registration and login
- Admin and user roles
- NFT listing, buying, and selling
- Artwork upload and management
- Artist management (add, edit, delete)
- Demo payment modal for NFT purchases
- Pagination for NFT listings
- Secure password hashing
- Session-based authentication

## Requirements

- PHP 7.4+
- MySQL/MariaDB
- Composer (for dependencies, if any)
- XAMPP/LAMP/WAMP or similar local server

## Installation

1. **Clone the repository:**
    ```bash
    git clone https://github.com/dishanta-adhikari/nft-marketplace-app.git
    ```

2. **Import the database:**
    - Import the provided SQL file (if available) into your MySQL server.
    - Update database credentials in `Config/Config.php` if needed.

3. **Set up your server:**
    - Place the project folder in your web server's root (e.g., `htdocs` for XAMPP).
    - Make sure the `uploads/` directory is writable.

4. **Start the server:**
    - Run Apache and MySQL via XAMPP or your preferred stack.
    - Visit `http://localhost/nft-marketplace-app` in your browser.

## Folder Structure

```
Config/         # Database and URL configuration
App/            # Core application logic
Views/          # All PHP views (user, admin, auth, components)
Assets/         # CSS, JS, images
uploads/        # Uploaded artwork images
```

## Default Admin Login

- Email: `admin@example.com`
- Password: `1234`
- (Change these in the database after first login.)

## Default User Login

- Email: `user1@example.com`
- Password: `1234`
- (Change these in the database after first login.)

## License

This project is for educational/demo purposes.

---

**Note:**  
Payments are simulated for demo purposes only. Do not use in production without implementing real payment processing and security best practices.