<?php
require_once '../../config/config.php';
require_once '../../models/User.php';

// Check if request is AJAX
header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

// Check if user has admin role
if (!userHasRole($_SESSION['user_id'], 'admin')) {
    echo json_encode(['success' => false, 'message' => 'No tiene permisos para realizar esta acción']);
    exit;
}

// Check required parameters
if (!isset($_POST['user_id']) || !isset($_POST['role_id']) || !isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'Parámetros incompletos']);
    exit;
}

// Get parameters
$user_id = $_POST['user_id'];
$role_id = $_POST['role_id'];
$action = $_POST['action'];

// Validate parameters
if (!is_numeric($user_id) || !is_numeric($role_id) || !in_array($action, ['assign', 'remove'])) {
    echo json_encode(['success' => false, 'message' => 'Parámetros inválidos']);
    exit;
}

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Initialize User object
$user = new User($db);
$user->id = $user_id;

// Perform action
$success = false;
if ($action === 'assign') {
    $success = $user->assignRole($role_id);
} else {
    $success = $user->removeRole($role_id);
}

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar rol']);
}
?>