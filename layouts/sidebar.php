<?php
$current_page = getCurrentPage();
?>
<aside class="sidebar">
    <nav class="sidebar-nav">
        <ul>
            <li class="<?= ($current_page === 'index.php') ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>index.php">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="<?= (strpos($current_page, 'usuarios') !== false) ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>users/index.php">
                    <i class="fas fa-users"></i> Usuarios
                </a>
            </li>
            <li class="<?= (strpos($current_page, 'roles') !== false) ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>roles/index.php">
                    <i class="fas fa-user-tag"></i> Roles
                </a>
            </li>
            <li class="<?= ($current_page === 'perfil.php') ? 'active' : '' ?>">
                <a href="<?= BASE_URL ?>users/profile.php">
                    <i class="fas fa-user-circle"></i> Mi Perfil
                </a>
            </li>
        </ul>
    </nav>
</aside>