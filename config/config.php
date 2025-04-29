<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Base URL !! IMPORTANTE DEFINIR CORRECTAMENTE EN SERVIDORES CON BAJA ESTRUCTURACIOn!!(apache de laragon)
define('BASE_URL', '/proc4/');

// Error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Application paths
define('ROOT_PATH', dirname(__DIR__) . '/');
define('INCLUDES_PATH', ROOT_PATH . 'includes/');
define('LAYOUTS_PATH', ROOT_PATH . 'layouts/');
define('ASSETS_PATH', ROOT_PATH . 'assets/');

// Authentication
define('SESSION_TIMEOUT', 1800); // 30 minutes

// Include essential files
require_once(ROOT_PATH . 'config/database.php');
require_once(INCLUDES_PATH . 'functions.php');
require_once(INCLUDES_PATH . 'auth.php');
?>