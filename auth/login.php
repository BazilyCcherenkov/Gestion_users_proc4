<?php
require_once '../config/config.php';

// Check if already logged in
if (isLoggedIn()) {
    redirect('../index.php');
}

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate form data
    $errors = [];
    
    if (empty($email)) {
        $errors[] = 'El correo electrónico es obligatorio';
    } elseif (!isValidEmail($email)) {
        $errors[] = 'El correo electrónico no es válido';
    }
    
    if (empty($password)) {
        $errors[] = 'La contraseña es obligatoria';
    }
    
    // If no errors, attempt to authenticate
    if (empty($errors)) {
        $user = authenticateUser($email, $password);
        
        if ($user) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_email'] = $user['correo'];
            
            // Redirect to dashboard
            redirect('../index.php');
        } else {
            $errors[] = 'Correo electrónico o contraseña incorrectos';
        }
    }
}

// Include header without nav
include_once '../layouts/header.php';
?>

<div class="login-container">
    <div class="login-form">
        <h2 class="login-title">Iniciar sesión</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
            <div class="form-group">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= $email ?? '' ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <div class="password-field">
                    <input type="password" id="password" name="password" class="form-control" required>
                    <button type="button" class="password-toggle" data-password-field="password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
            </div>
        </form>
    </div>
</div>

<?php
// Include footer
include_once '../layouts/footer.php';
?>