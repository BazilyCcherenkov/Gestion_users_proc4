<?php
require_once '../config/config.php';
require_once '../models/User.php';

// Check if user is logged in
requireLogin();

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Initialize User object
$user = new User($db);
$user->id = $_SESSION['user_id'];

// Get user details
if (!$user->getById()) {
    setErrorMessage('Usuario no encontrado');
    redirect('../index.php');
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $user->nombre = sanitizeInput($_POST['nombre'] ?? '');
    $user->correo = sanitizeInput($_POST['correo'] ?? '');
    $user->password = $_POST['password'] ?? '';
    
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
            // Update session data
            $_SESSION['user_name'] = $user->nombre;
            $_SESSION['user_email'] = $user->correo;
            
            setSuccessMessage('Perfil actualizado exitosamente');
            redirect('profile.php');
        } else {
            $errors[] = 'Ocurrió un error al actualizar el perfil';
        }
    }
}

// Get user roles
$roles = getUserRoles($user->id);

// Include header
include_once '../layouts/header.php';
?>

<div class="container">
    <?php include_once '../layouts/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h2 class="page-title">Mi Perfil</h2>
        </div>
        
        <?php displayMessages(); ?>
        
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
                <h3 class="card-title">Información Personal</h3>
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
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Mis Roles</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($roles)): ?>
                    <ul class="role-list">
                        <?php foreach ($roles as $role): ?>
                            <li>
                                <span class="badge badge-primary"><?= $role['nombre'] ?></span>
                                <p><?= $role['descripcion'] ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No tiene roles asignados.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<?php
// Include footer
include_once '../layouts/footer.php';
?>