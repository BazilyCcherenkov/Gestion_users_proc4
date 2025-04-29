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

// Get users
$stmt = $user->getAll();

// Include header
include_once '../layouts/header.php';
?>

<div class="container">
    <?php include_once '../layouts/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h2 class="page-title">Gestión de Usuarios</h2>
            <?php if (userHasAnyRole($_SESSION['user_id'], ['admin', 'editor'])): ?>
            <a href="add.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Usuario
            </a>
            <?php endif; ?>
        </div>
        
        <?php displayMessages(); ?>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista de Usuarios</h3>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Roles</th>
                                <th>Fecha de Creación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($stmt->rowCount() > 0): ?>
                                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['nombre'] ?></td>
                                        <td><?= $row['correo'] ?></td>
                                        <td>
                                            <?php 
                                            $roles = getUserRoles($row['id']);
                                            foreach ($roles as $role) {
                                                echo '<span class="badge badge-primary">' . $role['nombre'] . '</span> ';
                                            }
                                            ?>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($row['creado_en'])) ?></td>
                                        <td class="actions">
                                            <a href="view.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if (canEditUser($_SESSION['user_id'], $row['id'])): ?>
                                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if (userHasRole($_SESSION['user_id'], 'admin')): ?>
                                            <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm btn-delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No hay usuarios registrados</td>
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