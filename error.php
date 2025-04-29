<?php
require_once 'config/config.php';

// Get error message from session if exists
$error_message = $_SESSION['error_message'] ?? 'Ha ocurrido un error inesperado';
$error_details = $_SESSION['error_details'] ?? '';

// Clear error messages from session
unset($_SESSION['error_message']);
unset($_SESSION['error_details']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Sistema de Administración</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            padding: 2rem;
        }
        
        .error-content {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        
        .error-icon {
            font-size: 4rem;
            color: #e74c3c;
            margin-bottom: 1rem;
        }
        
        .error-title {
            font-size: 1.5rem;
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        
        .error-message {
            color: #7f8c8d;
            margin-bottom: 2rem;
        }
        
        .error-details {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 2rem;
            text-align: left;
            font-family: monospace;
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-content">
            <i class="fas fa-exclamation-circle error-icon"></i>
            <h1 class="error-title">¡Ups! Algo salió mal</h1>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
            
            <?php if (!empty($error_details) && isset($_SESSION['user_id']) && userHasRole($_SESSION['user_id'], 'admin')): ?>
            <div class="error-details">
                <?= htmlspecialchars($error_details) ?>
            </div>
            <?php endif; ?>
            
            <a href="<?= BASE_URL ?>index.php" class="btn btn-primary">
                <i class="fas fa-home"></i> Volver al Inicio
            </a>
        </div>
    </div>
</body>
</html>