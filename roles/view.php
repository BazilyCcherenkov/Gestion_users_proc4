<?php
require_once '../config/config.php';
require_once '../models/Role.php';

// Check if user is logged in
requireLogin();

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

// Include header
include_once '../layouts/header.php';
?>

<div class="container">
    <?php include_once '../layouts/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h2 class="page-title">Detalles del Rol</h2>
            <div>
                <a href="edit.php?id=<?= $role->id ?>" class="btn btn-warning">
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
                <h3 class="card-title">Información del Rol</h3>
            </div>
            <div class="card-body">
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
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Usuarios con este Rol</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($users)): ?>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= $user['id'] ?></td>
                                        <td><?= $user['nombre'] ?></td>
                                        <td><?= $user['correo'] ?></td>
                                        <td>
                                            <a href="../users/view.php?id=<?= $user['id'] ?>" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>No hay usuarios asignados a este rol.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<?php
// Include footer
include_once '../layouts/footer.php';
?>