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

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $role->nombre = sanitizeInput($_POST['nombre'] ?? '');
    $role->descripcion = sanitizeInput($_POST['descripcion'] ?? '');
    
    // Validate form data
    $errors = [];
    
    if (empty($role->nombre)) {
        $errors[] = 'El nombre es obligatorio';
    } elseif ($role->nameExists($role->nombre, $role->id)) {
        $errors[] = 'El nombre del rol ya existe';
    }
    
    if (empty($role->descripcion)) {
        $errors[] = 'La descripción es obligatoria';
    }
    
    // If no errors, update role
    if (empty($errors)) {
        if ($role->update()) {
            setSuccessMessage('Rol actualizado exitosamente');
            redirect('index.php');
        } else {
            $errors[] = 'Ocurrió un error al actualizar el rol';
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
            <h2 class="page-title">Editar Rol</h2>
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
                <h3 class="card-title">Información del Rol</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label for="nombre" class="form-label">Nombre del rol</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" value="<?= $role->nombre ?>" required>
                        <small class="form-text">El nombre debe ser único y descriptivo.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea id="descripcion" name="descripcion" class="form-control" rows="3" required><?= $role->descripcion ?></textarea>
                        <small class="form-text">Describe qué permisos o funciones tendrá este rol.</small>
                    </div>
                    
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