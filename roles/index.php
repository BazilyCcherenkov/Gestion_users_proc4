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

// Get roles
$stmt = $role->getAll();

// Include header
include_once '../layouts/header.php';
?>

<div class="container">
    <?php include_once '../layouts/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h2 class="page-title">Gestión de Roles</h2>
            <a href="add.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Rol
            </a>
        </div>
        
        <?php displayMessages(); ?>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista de Roles</h3>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Usuarios</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($stmt->rowCount() > 0): ?>
                                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                    <?php 
                                    // Get users count for this role
                                    $role->id = $row['id'];
                                    $users = $role->getUsers();
                                    $users_count = count($users);
                                    ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['nombre'] ?></td>
                                        <td><?= $row['descripcion'] ?></td>
                                        <td><?= $users_count ?> usuarios</td>
                                        <td class="actions">
                                            <a href="view.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm btn-delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No hay roles registrados</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<?php
// Include footer
include_once '../layouts/footer.php';
?>