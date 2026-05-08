<?php   
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 --------------------------------------------------------------
 Effettua la scrittura di un record in una tabella di database.
 Il database è già connesso e la transazione pronta.
 --------------------------------------------------------------
*/
if (session_status() === PHP_SESSION_NONE) {
    // write_art.php dovrebbe già aver fatto session_start(), ma lo assicuriamo
    session_start();
}


if (!isset($_SESSION['tab'])) {
    throw new Exception('Tabella non definita in sessione.');
}

// Assicuriamoci che esista una connessione PDO ($PDO). Se non esiste, la creiamo (con charset UTF-8mb4).
if (!isset($PDO) || !($PDO instanceof PDO)) {
    // CORRETTO: DSN con charset concatenato correttamente
    $con = "mysql:host=" . DB::$host . ";dbname=" . DB::$db . ";charset=UTF-8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    echo "<br>// Prima di new PDO"; // debug
    $PDO = new PDO($con, DB::$user, DB::$pw, $options);
    echo "<br>// NOTA: non facciamo beginTransaction qui perché il commento originale dice 'transazione pronta'"; // debug
}

// Leggiamo la struttura della tabella
$sql = "SHOW FULL COLUMNS FROM `" . DB::$pref . $_SESSION['tab'] . "`";
$columns = $PDO->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$cols = [];
$placeholders = [];
$params = [];
$i = 0;

foreach ($columns as $row) {
    // il comportamento originale saltava il primo campo (chiave/autoincrement) inserendo NULL
    if ($i === 0) {
        // saltiamo l'inserimento esplicito della chiave (lasciamo che il DB gestisca autoincrement)
        $i++;
        continue;
    }
    $field = $row['Field'];
    $cols[] = "`$field`";
    $placeholders[] = "?";
    $val = isset($_POST[$field]) ? $_POST[$field] : null;
    $params[] = $val;
    $i++;
}

if (count($cols) === 0) {
    throw new Exception('Nessun campo da inserire trovato per la tabella.');
}

// Costruzione ed esecuzione prepared statement
$cols_str = implode(',', $cols);
$placeholders_str = implode(',', $placeholders);
$insert_sql = "INSERT INTO `" . DB::$pref . $_SESSION['tab'] . "` ($cols_str) VALUES ($placeholders_str)";

try {
    $stmt = $PDO->prepare($insert_sql);
    $stmt->execute($params);
    $PDO->commit();
    }
    catch (PDOException $e) 
    {
    // Gestione dell'errore PDO
    error_log("Errore PDO: " . $e->getMessage());  // Salva il messaggio di errore nei log
    // Puoi mostrare un messaggio generico all'utente (senza dettagli tecnici)
    die("Si è verificato un errore durante l'operazione. Riprova più tardi.");
}

    if (!isset($_SESSION['esito']) || !is_array($_SESSION['esito'])) {
        $_SESSION['esito'] = [];
    }
    array_push($_SESSION['esito'], '54');
    if (!isset($_SESSION['esito']) || !is_array($_SESSION['esito'])) {
        $_SESSION['esito'] = [];       }
    array_push($_SESSION['esito'], 'ERR_INSERT');
?>