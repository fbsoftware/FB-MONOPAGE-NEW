<?php 
/** 
--------------------------------------------------------------------
Effettua la cancellazione di un record in una tabella di database. 
Il database è già connesso e la transazione pronta. 
--------------------------------------------------------------------*/ 
if (!isset($_SESSION['tab'])) 
{ throw new Exception('Tabella non definita in sessione.'); } 
// Assicuriamoci che esista una connessione PDO ($PDO). 
// se non esiste, la creiamo (con charset UTF-8mb4). 
if (!isset($PDO) || !($PDO instanceof PDO)) 
{ 
    $con = "mysql:host=" . DB::$host . ";dbname=" . DB::$db . ";charset=UTF-8mb4"; 
    $options = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
                PDO::ATTR_EMULATE_PREPARES => false, ]; 
$PDO = new PDO($con, DB::$user, DB::$pw, $options); 
} 
// Leggiamo i campi per individuare la chiave primaria (primo campo come prima) 
$sql = "SHOW FULL COLUMNS FROM `" . DB::$pref . $_SESSION['tab'] . "`"; 
        $columns = $PDO->query($sql)->fetchAll(PDO::FETCH_ASSOC); 
        if (count($columns) === 0) 
        { throw new Exception('Nessun campo trovato per la tabella.'); 
        } 
        $key_field = $columns[0]['Field']; 
        if (!isset($_POST[$key_field])) 
            { throw new Exception("Valore chiave primaria $key_field non trovato in POST."); 
            } 
            $delete_sql = "DELETE FROM `" . DB::$pref . $_SESSION['tab'] . "` WHERE `$key_field` = ?"; 
            try { $stmt = $PDO->prepare($delete_sql); 
            $stmt->execute([$_POST[$key_field]]); 
            $PDO->commit(); 
            array_push($_SESSION['esito'], '53'); 
            } 
            catch (PDOException $e) 
            { array_push($_SESSION['esito'], 'ERR_DELETE'); throw $e; } 
            ?>