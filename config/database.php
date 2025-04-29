<?php
/**
 * Database connection configuration
 */
class Database {
    private $host = 'localhost';
    private $db_name = 'admin_usuarios';
    private $username = 'root';
    private $password = 'root';
    private $conn;

    /**
     * Get database connection
     * 
     * @return PDO
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
            
            // Check if users table exists and has at least one user
            $stmt = $this->conn->query("SELECT COUNT(*) FROM usuarios");
            $userCount = $stmt->fetchColumn();
            
            if ($userCount === 0) {
                $_SESSION['error_message'] = 'No hay usuarios registrados en el sistema';
                $_SESSION['error_details'] = 'La tabla usuarios está vacía. Por favor, ejecute el script de inicialización de la base de datos.';
                header('Location: ' . BASE_URL . 'error.php');
                exit;
            }
            
        } catch(PDOException $e) {
            $_SESSION['error_message'] = 'Error de conexión a la base de datos';
            $_SESSION['error_details'] = $e->getMessage();
            header('Location: ' . BASE_URL . 'error.php');
            exit;
        }

        return $this->conn;
    }
}
?>
