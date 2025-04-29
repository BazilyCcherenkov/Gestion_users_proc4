<?php
require_once '../config/config.php';
require_once '../models/User.php';
require_once '../models/Role.php';

// Check if user is logged in
requireLogin();

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Initialize User object
$user = new User($db);

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

// Get user roles
$roles = getUserRoles($user->id);

// Include header
include_once '../layouts/header.php';
?>

<div class="container">
    <?php include_once '../layouts/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h2 class="page-title">Detalles del Usuario</h2>
            <div>
                <a href="edit.php?id=<?= $user->id ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="index.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
        
        <?php displayMessages(); ?>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Información del Usuario</h3>
            </div>
            <div class="card-body">
                <div class="user-details">
                    <div class="form-group">
                        <label class="form-label">ID:</label>
                        <p><?= $user->id ?></p>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Nombre:</label>
                        <p><?= $user->nombre ?></p>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Correo Electrónico:</label>
                        <p><?= $user->correo ?></p>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Fecha de Creación:</label>
                        <p><?= date('d/m/Y H:i', strtotime($user->creado_en)) ?></p>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Roles:</label>
                        <div>
                            <?php if (!empty($roles)): ?>
                                <?php foreach ($roles as $role): ?>
                                    <span class="badge badge-primary"><?= $role['nombre'] ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Este usuario no tiene roles asignados.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php
// Include footer
include_once '../layouts/footer.php';
?>