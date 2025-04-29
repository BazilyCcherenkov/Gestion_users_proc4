<?php
require_once '../config/config.php';
require_once '../models/User.php';
require_once '../models/Role.php';

// Check if user is logged in
requireLogin();

// Check if user has permission to create users
requireAnyRole(['admin', 'editor']);

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Initialize User and Role objects
$user = new User($db);
$role = new Role($db);

// Get all roles
$roles_stmt = $role->getAll();
$roles = [];
while ($role_row = $roles_stmt->fetch(PDO::FETCH_ASSOC)) {
    $roles[] = $role_row;
}

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
    } elseif ($user->emailExists($user->correo)) {
        $errors[] = 'El correo electrónico ya está registrado';
    }
    
    if (empty($user->password)) {
        $errors[] = 'La contraseña es obligatoria';
    } elseif (strlen($user->password) < 6) {
        $errors[] = 'La contraseña debe tener al menos 6 caracteres';
    }
    
    // If no errors, create user
    if (empty($errors)) {
        if ($user->create()) {
            // Assign selected roles
            foreach ($selected_roles as $role_id) {
                $user->assignRole($role_id);
            }
            
            setSuccessMessage('Usuario creado exitosamente');
            redirect('index.php');
        } else {
            $errors[] = 'Ocurrió un error al crear el usuario';
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
            <h2 class="page-title">Crear Nuevo Usuario</h2>
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
                <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
                    <div class="form-group">
                        <label for="nombre" class="form-label">Nombre completo</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" value="<?= $user->nombre ?? '' ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="correo" class="form-label">Correo electrónico</label>
                        <input type="email" id="correo" name="correo" class="form-control" value="<?= $user->correo ?? '' ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="password-field">
                            <input type="password" id="password" name="password" class="form-control" required>
                            <button type="button" class="password-toggle" data-password-field="password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Roles</label>
                        <?php foreach ($roles as $role): ?>
                            <div class="form-check">
                                <input type="checkbox" id="role_<?= $role['id'] ?>" name="roles[]" value="<?= $role['id'] ?>" class="form-check-input">
                                <label for="role_<?= $role['id'] ?>" class="form-check-label">
                                    <?= $role['nombre'] ?> - <?= $role['descripcion'] ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Guardar</button>
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