<?php
class Database {
    private static $instance = null;  // L'istanza della classe
    private $connection;  // Connessione PDO

    // Parametri di connessione
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'mio_database';

    // Costruttore privato per impedire istanze multiple
    private function __construct() {
        try {
            // Creazione della connessione PDO
            $dsn = "mysql:host=$this->host;dbname=$this->dbname;charset=utf8";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            // Imposta l'errore in modalità eccezione per una gestione degli errori più chiara
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connessione al database fallita: " . $e->getMessage());
        }
    }

    // Metodo per ottenere l'istanza della connessione (o crearne una nuova)
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Metodo per ottenere la connessione PDO
    public function getConnection() {
        return $this->connection;
    }

    // Prevenire la clonazione dell'oggetto
    private function __clone() {}

    // Prevenire la deserializzazione dell'oggetto
    private function __wakeup() {}
}

// Esempio di utilizzo:
$db = Database::getInstance(); // Ottieni l'istanza del database
$conn = $db->getConnection();  // Ottieni la connessione PDO

/**
Spiegazione delle modifiche:

PDO (PHP Data Objects):  ho usato PDO per la connessione al database. 
PDO è un'interfaccia che supporta più database, non solo MySQL, ed è più flessibile rispetto a MySQLi.
Connessione PDO: La connessione è stabilita tramite un Data Source Name (DSN), che include il tipo 
di database (mysql), l'host e il nome del database. 
La connessione viene poi creata utilizzando new PDO().
Errori in modalità eccezione: Ho impostato l'attributo PDO::ATTR_ERRMODE a PDO::ERRMODE_EXCEPTION, 
che fa sì che eventuali errori di connessione o query vengano lanciati come eccezioni, rendendo più 
emplice la gestione degli errori.
 */
?>