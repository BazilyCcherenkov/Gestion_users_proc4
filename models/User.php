<?php
/**
 * User model for database operations
 */
class User {
    // Database connection
    private $conn;
    private $table_name = "usuarios";
    
    // User properties
    public $id;
    public $nombre;
    public $correo;
    public $password;
    public $creado_en;
    
    /**
     * Constructor with database connection
     * 
     * @param PDO $db Database connection
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Get all users
     * 
     * @return PDOStatement
     */
    public function getAll() {
        $query = "SELECT id, nombre, correo, creado_en FROM " . $this->table_name . " ORDER BY nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * Get single user by ID
     * 
     * @return bool True if found, false otherwise
     */
    public function getById() {
        $query = "SELECT id, nombre, correo, creado_en FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->correo = $row['correo'];
            $this->creado_en = $row['creado_en'];
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Create new user
     * 
     * @return bool Success status
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (nombre, correo, password) VALUES (:nombre, :correo, :password)";
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $this->nombre = sanitizeInput($this->nombre);
        $this->correo = sanitizeInput($this->correo);
        $this->password = hash('sha256', $this->password);
        
        // Bind values
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':correo', $this->correo);
        $stmt->bindParam(':password', $this->password);
        
        // Execute query
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        
        return false;
    }
    
    /**
     * Update existing user
     * 
     * @return bool Success status
     */
    public function update() {
        // If password is provided, update with password, otherwise update without password
        if (!empty($this->password)) {
            $query = "UPDATE " . $this->table_name . " SET nombre = :nombre, correo = :correo, password = :password WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            
            // Hash the password
            $this->password = hash('sha256', $this->password);
            $stmt->bindParam(':password', $this->password);
        } else {
            $query = "UPDATE " . $this->table_name . " SET nombre = :nombre, correo = :correo WHERE id = :id";
            $stmt = $this->conn->prepare($query);
        }
        
        // Sanitize inputs
        $this->nombre = sanitizeInput($this->nombre);
        $this->correo = sanitizeInput($this->correo);
        
        // Bind values
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':correo', $this->correo);
        $stmt->bindParam(':id', $this->id);
        
        // Execute query
        return $stmt->execute();
    }
    
    /**
     * Delete user
     * 
     * @return bool Success status
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        // Bind param
        $stmt->bindParam(':id', $this->id);
        
        // Execute query
        return $stmt->execute();
    }
    
    /**
     * Check if email already exists
     * 
     * @param string $email Email to check
     * @param int|null $exclude_id User ID to exclude (for updates)
     * @return bool True if exists, false otherwise
     */
    public function emailExists($email, $exclude_id = null) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE correo = :email";
        
        if ($exclude_id !== null) {
            $query .= " AND id != :exclude_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        
        if ($exclude_id !== null) {
            $stmt->bindParam(':exclude_id', $exclude_id);
        }
        
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Assign role to user
     * 
     * @param int $role_id Role ID
     * @return bool Success status
     */
    public function assignRole($role_id) {
        // Check if the role is already assigned
        $query = "SELECT * FROM usuario_rol WHERE usuario_id = :user_id AND rol_id = :role_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->id);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Role already assigned
            return true;
        }
        
        // Assign the role
        $query = "INSERT INTO usuario_rol (usuario_id, rol_id) VALUES (:user_id, :role_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->id);
        $stmt->bindParam(':role_id', $role_id);
        
        return $stmt->execute();
    }
    
    /**
     * Remove role from user
     * 
     * @param int $role_id Role ID
     * @return bool Success status
     */
    public function removeRole($role_id) {
        $query = "DELETE FROM usuario_rol WHERE usuario_id = :user_id AND rol_id = :role_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->id);
        $stmt->bindParam(':role_id', $role_id);
        
        return $stmt->execute();
    }
    
    /**
     * Get assigned roles
     * 
     * @return array Array of role IDs
     */
    public function getAssignedRoles() {
        $query = "SELECT rol_id FROM usuario_rol WHERE usuario_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->id);
        $stmt->execute();
        
        $roles = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = $row['rol_id'];
        }
        
        return $roles;
    }
}
?>