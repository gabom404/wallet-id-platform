<?php
/**
 * WalletID Platform - Admin Database Connection
 * Decoupled configuration with root config.php support and environment variable overrides.
 */

// Load root or local configuration if present
if (file_exists(__DIR__ . '/../config.php')) {
    require_once __DIR__ . '/../config.php';
} elseif (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}

$host = defined('DB_HOST') ? DB_HOST : (getenv('DB_HOST') ?: 'localhost');
$user = defined('DB_USER') ? DB_USER : (getenv('DB_USER') ?: 'root');
$clave = defined('DB_PASS') ? DB_PASS : (getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
$bd = defined('DB_NAME') ? DB_NAME : (getenv('DB_NAME') ?: 'wallet_id');
$port = defined('DB_PORT') ? (int)DB_PORT : (int)(getenv('DB_PORT') ?: 3306);

// Attempt database connection
$conexion = @mysqli_connect($host, $user, $clave, $bd, $port);

if (!$conexion) {
    // If called from src/ directory or root admin/
    $errorPath = file_exists(__DIR__ . '/error_500.html') ? 'error_500.html' : '../error_500.html';
    if (!headers_sent()) {
        header("Location: $errorPath");
        exit();
    } else {
        die("Error de conexión a la base de datos: " . mysqli_connect_error());
    }
}

mysqli_select_db($conexion, $bd) or die("Error al seleccionar la base de datos: " . mysqli_error($conexion));
mysqli_set_charset($conexion, defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4');

require_once __DIR__ . '/../lib/safe_query.php';
