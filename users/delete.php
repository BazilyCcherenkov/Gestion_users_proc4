<?php
require_once '../config/config.php';
require_once '../models/User.php';

// Check if user is logged in
requireLogin();

// Check if user has admin role
requireRole('admin');

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

// Check if this is the current user
if ($_GET['id'] == $_SESSION['user_id']) {
    setErrorMessage('No puede eliminar su propio usuario');
    redirect('index.php');
}

// Get user details
if (!$user->getById()) {
    setErrorMessage('Usuario no encontrado');
    redirect('index.php');
}

// Process deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    if ($user->delete()) {
        setSuccessMessage('Usuario eliminado exitosamente');
    } else {
        setErrorMessage('Ocurrió un error al eliminar el usuario');
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
            <h2 class="page-title">Eliminar Usuario</h2>
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
                    <strong>¿Está seguro de que desea eliminar este usuario?</strong>
                    <p>Esta acción no se puede deshacer y eliminará todos los datos asociados con el usuario.</p>
                </div>
                
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