<?php
/** 
--------------------------------------------------------------    
Effettua la modifica di un record in una tabella di database.
Il database è già connesso e la transazione pronta.
--------------------------------------------------------------*/
if (!isset($_SESSION['tab'])) {
    throw new Exception('Tabella non definita in sessione.');
}

// Assicuriamoci che esista una connessione PDO ($PDO). Se non esiste, la creiamo (con charset UTF-8mb4).
if (!isset($PDO) || !($PDO instanceof PDO)) {
    $con = "mysql:host=" . DB::$host . ";dbname=" . DB::$db . ";charset=UTF-8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $PDO = new PDO($con, DB::$user, DB::$pw, $options);
    // NOTA: non facciamo beginTransaction qui perché il commento originale dice "transazione pronta".
}

// Leggiamo la struttura della tabella
$sql = "SHOW FULL COLUMNS FROM `" . DB::$pref . $_SESSION['tab'] . "`";
$columns = $PDO->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$set_parts = [];
$params = [];
$i = 0;
$where_field = null;
$where_value = null;

foreach ($columns as $row) {
    $field = $row['Field'];
    if ($i === 0) {
        // Prima colonna => chiave primaria / id per WHERE
        $where_field = $field;
        if (!isset($_POST[$field])) {
            throw new Exception("Chiave primaria $field non trovata nei dati POST.");
        }
        $where_value = $_POST[$field];
        $i++;
        continue;
    }
    // aggiungi al SET
    $set_parts[] = "`$field` = ?";
    $val = isset($_POST[$field]) ? $_POST[$field] : null;
    $params[] = $val;
    $i++;
}

if ($where_field === null) {
    throw new Exception('Chiave primaria non individuata.');
}

if (count($set_parts) === 0) {
    // niente da aggiornare
    array_push($_SESSION['esito'], 'ERR_NO_FIELDS');
} else {
    // Aggiungiamo in coda il valore per la clausola WHERE
    $params[] = $where_value;
    $set_sql = implode(',', $set_parts);
    $update_sql = "UPDATE `" . DB::$pref . $_SESSION['tab'] . "` SET $set_sql WHERE `$where_field` = ?";

    try {
        $stmt = $PDO->prepare($update_sql);
        $stmt->execute($params);
        $PDO->commit();
        array_push($_SESSION['esito'], '55');
        // debug opzionale (se vuoi rimuovere in produzione)
        // print_r($set_parts);
    } catch (PDOException $e) {
        array_push($_SESSION['esito'], 'ERR_UPDATE');
        throw $e;
    }
}
?>