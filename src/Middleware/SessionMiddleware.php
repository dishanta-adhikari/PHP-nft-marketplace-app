<?php

namespace App\Middleware;

use Exception;

class SessionMiddleware
{
    public static function verifyUser(): void
    {
        if (
            !empty($_SESSION['user_id']) &&
            $_SESSION['role'] !== 'admin'
        ) {
            header("Location: " . APP_URL);
            exit;
        }
    }

    public static function verifyAdmin(): void
    {
        if (
            !empty($_SESSION['user_id']) &&
            $_SESSION['role'] !== 'user'
        ) {
            header("Location: " . APP_URL . '/admin/dashboard');
            exit;
        }
    }


    public static function validateUserSession(): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
            header("Location: " . APP_URL . "/login");
            exit;
        }
    }

    public static function validateAdminSession(): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: " . APP_URL . "/login");
            exit;
        }
    }

    public static function validateOTP()
    {
        if (empty($_SESSION['otp_verified'])) {
            $_SESSION['error'] = "Access denied. Please verify OTP first.";
            header("Location: " . APP_URL . "/forgot-password");
            exit;
        }
    }
}
