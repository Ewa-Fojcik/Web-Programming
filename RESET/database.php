<?php
class Database {
    // Private properties for database connection
    private $host = 'studdb.csc.liv.ac.uk';
    private $db   = 'sgefojci';
    private $user = 'sgefojci';
    private $pass = 'Incorrect'; 
    private $charset = 'utf8mb4';

    public $pdo;
    /**
     * Constructor creates a new PDO connection
     */
    public function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        );

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            // Handle connection errors
            exit('Database connection failed: ' . $e->getMessage());
        }
    }

}
?>
