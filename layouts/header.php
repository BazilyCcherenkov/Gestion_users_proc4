<?php
require_once dirname(__DIR__) . '/config/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Administración de Usuarios</title>
    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/styles.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php if (isLoggedIn()): ?>
            <header class="main-header">
                <div class="logo">
                    <h1>AdminUsuarios</h1>
                </div>
                <div class="user-info">
                    <span><?= $_SESSION['user_name'] ?></span>
                    <a href="<?= BASE_URL ?>auth/logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                </div>
            </header>
        <?php endif; ?>