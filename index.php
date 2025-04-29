<?php
require_once 'config/config.php';
require_once 'models/User.php';
require_once 'models/Role.php';

// Check if user is logged in
requireLogin();

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Initialize models
$user = new User($db);
$role = new Role($db);

// Get stats
$users_stmt = $user->getAll();
$total_users = $users_stmt->rowCount();

$roles_stmt = $role->getAll();
$total_roles = $roles_stmt->rowCount();

$admin_count = 0;
$roles = [];
while ($row = $roles_stmt->fetch(PDO::FETCH_ASSOC)) {
    $roles[] = $row;
    if ($row['nombre'] === 'admin') {
        $role->id = $row['id'];
        $admin_users = $role->getUsers();
        $admin_count = count($admin_users);
    }
}

// Include header
include_once 'layouts/header.php';
?>

<div class="container">
    <?php include_once 'layouts/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h2 class="page-title">Dashboard</h2>
        </div>
        
        <?php displayMessages(); ?>
        
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon users">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value"><?= $total_users ?></div>
                <div class="stat-label">Usuarios registrados</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon roles">
                    <i class="fas fa-user-tag"></i>
                </div>
                <div class="stat-value"><?= $total_roles ?></div>
                <div class="stat-label">Roles disponibles</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon admin">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stat-value"><?= $admin_count ?></div>
                <div class="stat-label">Administradores</div>
            </div>
        </div>
        
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actividad reciente</h3>
                </div>
                <div class="card-body">
                    <p>Bienvenido al Sistema de Administración de Usuarios. Utilice la barra lateral para navegar entre las diferentes secciones del sistema.</p>
                    
                    <ul>
                        <li><a href="users/index.php">Administrar usuarios</a> - Ver, crear, editar y eliminar usuarios del sistema.</li>
                        <li><a href="roles/index.php">Administrar roles</a> - Ver, crear, editar y eliminar roles del sistema.</li>
                        <li><a href="users/profile.php">Mi perfil</a> - Ver y editar su información personal.</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</div>

<?php
// Include footer
include_once 'layouts/footer.php';
?>