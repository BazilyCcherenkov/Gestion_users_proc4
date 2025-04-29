<?php
require_once '../config/config.php';
require_once '../models/User.php';
require_once '../models/Role.php';

// Check if user is logged in
requireLogin();

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Initialize User and Role objects
$user = new User($db);
$role = new Role($db);

// Check if ID parameter exists
if (!isset($_GET['id'])) {
    setErrorMessage('ID de usuario no especificado');
    redirect('index.php');
}

// Get user ID from URL
$user->id = $_GET['id'];

// Get user details
if (!$user->getById()) {
    setErrorMessage('Usuario no encontrado');
    redirect('index.php');
}

// Check if user has permission to edit this user
if (!canEditUser($_SESSION['user_id'], $user->id)) {
    setErrorMessage('No tiene permiso para editar este usuario');
    redirect('index.php');
}

// Get all roles
$roles_stmt = $role->getAll();
$roles = [];
while ($role_row = $roles_stmt->fetch(PDO::FETCH_ASSOC)) {
    $roles[] = $role_row;
}

// Get user's assigned roles
$assigned_roles = $user->getAssignedRoles();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $user->nombre = sanitizeInput($_POST['nombre'] ?? '');
    $user->correo = sanitizeInput($_POST['correo'] ?? '');
    $user->password = $_POST['password'] ?? '';
    $selected_roles = $_POST['roles'] ?? [];
    
    // Validate form data
    $errors = [];
    
    if (empty($user->nombre)) {
        $errors[] = 'El nombre es obligatorio';
    }
    
    if (empty($user->correo)) {
        $errors[] = 'El correo electrónico es obligatorio';
    } elseif (!isValidEmail($user->correo)) {
        $errors[] = 'El correo electrónico no es válido';
    } elseif ($user->emailExists($user->correo, $user->id)) {
        $errors[] = 'El correo electrónico ya está registrado por otro usuario';
    }
    
    if (!empty($user->password) && strlen($user->password) < 6) {
        $errors[] = 'La contraseña debe tener al menos 6 caracteres';
    }
    
    // If no errors, update user
    if (empty($errors)) {
        if ($user->update()) {
            // Only admin and editor can modify roles
            if (userHasAnyRole($_SESSION['user_id'], ['admin', 'editor'])) {
                // Update roles
                // First get current roles to identify changes
                $current_roles = $assigned_roles;
                
                // Roles to add
                foreach ($selected_roles as $role_id) {
                    if (!in_array($role_id, $current_roles)) {
                        $user->assignRole($role_id);
                    }
                }
                
                // Roles to remove
                foreach ($current_roles as $role_id) {
                    if (!in_array($role_id, $selected_roles)) {
                        $user->removeRole($role_id);
                    }
                }
            }
            
            setSuccessMessage('Usuario actualizado exitosamente');
            redirect('index.php');
        } else {
            $errors[] = 'Ocurrió un error al actualizar el usuario';
        }
    }
}

// Include header
include_once '../layouts/header.php';
?>

<div class="container">
    <?php include_once '../layouts/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h2 class="page-title">Editar Usuario</h2>
            <a href="index.php" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Información del Usuario</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label for="nombre" class="form-label">Nombre completo</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" value="<?= $user->nombre ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="correo" class="form-label">Correo electrónico</label>
                        <input type="email" id="correo" name="correo" class="form-control" value="<?= $user->correo ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña (dejar en blanco para mantener la actual)</label>
                        <div class="password-field">
                            <input type="password" id="password" name="password" class="form-control">
                            <button type="button" class="password-toggle" data-password-field="password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <small class="form-text">Dejar en blanco para mantener la contraseña actual</small>
                    </div>
                    
                    <?php if (userHasAnyRole($_SESSION['user_id'], ['admin', 'editor'])): ?>
                    <div class="form-group">
                        <label class="form-label">Roles</label>
                        <?php foreach ($roles as $r): ?>
                            <div class="form-check">
                                <input type="checkbox" id="role_<?= $r['id'] ?>" name="roles[]" value="<?= $r['id'] ?>" class="form-check-input" <?= in_array($r['id'], $assigned_roles) ? 'checked' : '' ?>>
                                <label for="role_<?= $r['id'] ?>" class="form-check-label">
                                    <?= $r['nombre'] ?> - <?= $r['descripcion'] ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Guardar Cambios</button>
                        <a href="index.php" class="btn btn-danger">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php
// Include footer
include_once '../layouts/footer.php';
?>