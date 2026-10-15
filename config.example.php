<?php
/**
 * WalletID Platform - Global Configuration Template
 *
 * Instructions:
 * 1. Copy this file to config.php in the repository root:
 *    cp config.example.php config.php
 * 2. Configure your MySQL credentials and environment settings below.
 */

// Database Hostname / IP (default: localhost)
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');

// Database Port (default: 3306)
define('DB_PORT', getenv('DB_PORT') ? (int)getenv('DB_PORT') : 3306);

// Database Username (default: root)
define('DB_USER', getenv('DB_USER') ?: 'root');

// Database Password (default: empty for local XAMPP development)
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');

// Database Name (default: wallet_id)
define('DB_NAME', getenv('DB_NAME') ?: 'wallet_id');

// Character Set (default: utf8mb4)
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');

// Application Branding
define('APP_NAME', 'WalletID - Campus Digital Wallet');
define('APP_ENV', getenv('APP_ENV') ?: 'production');

// Authentication Security Salt
define('APP_SALT', 'HelloKitty');
