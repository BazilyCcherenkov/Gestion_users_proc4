<?php
/**
 * Authentication related functions
 */

/**
 * Authenticate a user
 * 
 * @param string $email User email
 * @param string $password User password
 * @return array|bool User data if authenticated, false otherwise
 */
function authenticateUser($email, $password) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT id, nombre, correo, password FROM usuarios WHERE correo = :email LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stored_password = $row['password'];
        $hashed_password = hash('sha256', $password);
        
        if ($stored_password === $hashed_password) {
            return [
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'correo' => $row['correo']
            ];
        }
    }
    
    return false;
}

/**
 * Check if user is logged in
 * 
 * @return bool True if logged in, false otherwise
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Require user to be logged in
 * 
 * @return void Redirects to login if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        setErrorMessage('Debe iniciar sesión para acceder a esta página');
        redirect('auth/login.php');
    }
}

/**
 * Get user roles
 * 
 * @param int $user_id User ID
 * @return array Array of user roles
 */
function getUserRoles($user_id) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT r.id, r.nombre, r.descripcion 
              FROM roles r 
              JOIN usuario_rol ur ON r.id = ur.rol_id 
              WHERE ur.usuario_id = :user_id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Check if user has specific role
 * 
 * @param int $user_id User ID
 * @param string $role_name Role name
 * @return bool True if user has role, false otherwise
 */
function userHasRole($user_id, $role_name) {
    $roles = getUserRoles($user_id);
    
    foreach ($roles as $role) {
        if ($role['nombre'] === $role_name) {
            return true;
        }
    }
    
    return false;
}

/**
 * Check if user has any of the specified roles
 * 
 * @param int $user_id User ID
 * @param array $role_names Array of role names
 * @return bool True if user has any of the roles, false otherwise
 */
function userHasAnyRole($user_id, array $role_names) {
    $roles = getUserRoles($user_id);
    
    foreach ($roles as $role) {
        if (in_array($role['nombre'], $role_names)) {
            return true;
        }
    }
    
    return false;
}

/**
 * Check if user can edit a specific user
 * 
 * @param int $editor_id ID of user trying to edit
 * @param int $target_id ID of user being edited
 * @return bool True if user can edit, false otherwise
 */
function canEditUser($editor_id, $target_id) {
    // Users can always edit their own profile
    if ($editor_id === $target_id) {
        return true;
    }
    
    // Admins can edit any user
    if (userHasRole($editor_id, 'admin')) {
        return true;
    }
    
    // Editors can edit non-admin users
    if (userHasRole($editor_id, 'editor') && !userHasRole($target_id, 'admin')) {
        return true;
    }
    
    return false;
}

/**
 * Require user to have a specific role
 * 
 * @param string $role_name Role name
 * @return void Redirects to dashboard if not authorized
 */
function requireRole($role_name) {
    requireLogin();
    
    if (!userHasRole($_SESSION['user_id'], $role_name)) {
        setErrorMessage('No tiene permiso para acceder a esta página');
        redirect('../index.php');
    }
}

/**
 * Require user to have any of the specified roles
 * 
 * @param array $role_names Array of role names
 * @return void Redirects to dashboard if not authorized
 */
function requireAnyRole(array $role_names) {
    requireLogin();
    
    if (!userHasAnyRole($_SESSION['user_id'], $role_names)) {
        setErrorMessage('No tiene permiso para acceder a esta página');
        redirect('../index.php');
    }
}

/**
 * Get current user data
 * 
 * @return array|null User data if logged in, null otherwise
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT id, nombre, correo, creado_en FROM usuarios WHERE id = :id LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $_SESSION['user_id']);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    return null;
}

/**
 * Logout current user
 * 
 * @return void
 */
/*
 function logoutUser() {
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Redirect to login page
    redirect('auth/login.php');
}
*/

function logoutUser() {
    $_SESSION = array();
    session_destroy();
    
    header("Location: " . BASE_URL . "auth/login.php");
    exit;
}


?>