<?php
/**
 * Common functions used throughout the application
 */

/**
 * Display a success message
 * 
 * @param string $message The message to display
 * @return void
 */
function setSuccessMessage($message) {
    $_SESSION['success_message'] = $message;
}

/**
 * Display an error message
 * 
 * @param string $message The message to display
 * @return void
 */
function setErrorMessage($message) {
    $_SESSION['error_message'] = $message;
}

/**
 * Handle critical error and redirect to error page
 * 
 * @param string $message User-friendly error message
 * @param string $details Technical error details (only shown to admins)
 * @return void
 */
function handleCriticalError($message, $details = '') {
    $_SESSION['error_message'] = $message;
    $_SESSION['error_details'] = $details;
    header('Location: ' . BASE_URL . 'error.php');
    exit;
}

/**
 * Display messages and clear them from session
 * 
 * @return void
 */
function displayMessages() {
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']);
    }
    
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }
}

/**
 * Sanitize input data
 * 
 * @param string $data Data to sanitize
 * @return string Sanitized data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Redirect to a URL
 * 
 * @param string $url URL to redirect to
 * @return void
 */
function redirect($url) {
    header('Location: ' . $url);
    exit;
}
/*
function redirect($url) {
    header('Location: ' . BASE_URL . $url);
    exit;
}
*/
/**
 * Check if a string is valid email
 * 
 * @param string $email Email to validate
 * @return bool True if valid, false otherwise
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Get current page name
 * 
 * @return string Current page name
 */
function getCurrentPage() {
    return basename($_SERVER['PHP_SELF']);
}
?>