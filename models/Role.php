<?php
/**
 * Role model for database operations
 */
class Role {
    // Database connection
    private $conn;
    private $table_name = "roles";
    
    // Role properties
    public $id;
    public $nombre;
    public $descripcion;
    
    /**
     * Constructor with database connection
     * 
     * @param PDO $db Database connection
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Get all roles
     * 
     * @return PDOStatement
     */
    public function getAll() {
        $query = "SELECT id, nombre, descripcion FROM " . $this->table_name . " ORDER BY nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * Get single role by ID
     * 
     * @return bool True if found, false otherwise
     */
    public function getById() {
        $query = "SELECT id, nombre, descripcion FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->descripcion = $row['descripcion'];
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Create new role
     * 
     * @return bool Success status
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (nombre, descripcion) VALUES (:nombre, :descripcion)";
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $this->nombre = sanitizeInput($this->nombre);
        $this->descripcion = sanitizeInput($this->descripcion);
        
        // Bind values
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
        
        // Execute query
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        
        return false;
    }
    
    /**
     * Update existing role
     * 
     * @return bool Success status
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nombre = :nombre, descripcion = :descripcion WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $this->nombre = sanitizeInput($this->nombre);
        $this->descripcion = sanitizeInput($this->descripcion);
        
        // Bind values
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':id', $this->id);
        
        // Execute query
        return $stmt->execute();
    }
    
    /**
     * Delete role
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
     * Check if role name already exists
     * 
     * @param string $nombre Role name to check
     * @param int|null $exclude_id Role ID to exclude (for updates)
     * @return bool True if exists, false otherwise
     */
    public function nameExists($nombre, $exclude_id = null) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE nombre = :nombre";
        
        if ($exclude_id !== null) {
            $query .= " AND id != :exclude_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        
        if ($exclude_id !== null) {
            $stmt->bindParam(':exclude_id', $exclude_id);
        }
        
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Get users with this role
     * 
     * @return array Array of users
     */
    public function getUsers() {
        $query = "SELECT u.id, u.nombre, u.correo 
                 FROM usuarios u 
                 JOIN usuario_rol ur ON u.id = ur.usuario_id 
                 WHERE ur.rol_id = :role_id 
                 ORDER BY u.nombre";
                 
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $this->id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>