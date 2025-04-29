<?php
require_once '../config/config.php';
require_once '../models/Role.php';

// Check if user is logged in
requireLogin();

// Check if user has admin role
requireRole('admin');

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Initialize Role object
$role = new Role($db);

// Check if ID parameter exists
if (!isset($_GET['id'])) {
    setErrorMessage('ID de rol no especificado');
    redirect('index.php');
}

// Get role ID from URL
$role->id = $_GET['id'];

// Get role details
if (!$role->getById()) {
    setErrorMessage('Rol no encontrado');
    redirect('index.php');
}

// Get users with this role
$users = $role->getUsers();
$user_count = count($users);

// Process deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    if ($role->delete()) {
        setSuccessMessage('Rol eliminado exitosamente');
    } else {
        setErrorMessage('Ocurrió un error al eliminar el rol');
    }
    redirect('index.php');
}

// Include header
include_once '../layouts/header.php';
?>

<div class="container">
    <?php include_once '../layouts/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h2 class="page-title">Eliminar Rol</h2>
            <a href="index.php" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Confirmar Eliminación</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <strong>¿Está seguro de que desea eliminar este rol?</strong>
                    <p>Esta acción no se puede deshacer y eliminará todas las asignaciones de este rol a usuarios.</p>
                    
                    <?php if ($user_count > 0): ?>
                        <p><strong>Aviso:</strong> Hay <?= $user_count ?> usuarios asignados a este rol. Al eliminar el rol, estos usuarios perderán los permisos asociados.</p>
                    <?php endif; ?>
                </div>
                
                <div class="role-details">
                    <div class="form-group">
                        <label class="form-label">ID:</label>
                        <p><?= $role->id ?></p>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Nombre:</label>
                        <p><?= $role->nombre ?></p>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Descripción:</label>
                        <p><?= $role->descripcion ?></p>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Usuarios asignados:</label>
                        <p><?= $user_count ?></p>
                    </div>
                </div>
                
                <form method="POST">
                    <input type="hidden" name="confirm_delete" value="1">
                    <div class="form-group">
                        <button type="submit" class="btn btn-danger">Confirmar Eliminación</button>
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
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