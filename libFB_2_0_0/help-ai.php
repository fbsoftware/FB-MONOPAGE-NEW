1️⃣ Funzione console_log() migliorata

Per vedere i valori direttamente nella console del browser, anche array e oggetti:

function console_log($data) {
    echo '<script>console.log('.json_encode($data, JSON_PRETTY_PRINT).');</script>';
}


Uso:

console_log($_POST);       // vedi tutti i dati del POST
console_log($rows);        // vedi l’array risultato della query
console_log("TEMA=".$tema); // stringhe semplici


Non genera errori PHP

Non richiede librerie esterne

JSON prettificato per leggibilità

2️⃣ Funzione dbg() rapida

Per stampare a video debug rapido in modo elegante:

function dbg($data, $exit=false) {
    echo '<pre>'; 
    print_r($data); 
    echo '</pre>';
    if($exit) exit;
}


Uso:

dbg($rows);        // stampa l’array in modo leggibile
dbg($_POST, true); // stampa e blocca l’esecuzione

3️⃣ Funzione safe_post()

Per leggere un campo POST in modo sicuro, senza errori “undefined index”:

function safe_post($key, $default='') {
    return $_POST[$key] ?? $default;
}


Uso:

$tema = safe_post('tema');


Evita Notice PHP

Puoi definire default a tuo piacimento

4️⃣ Prepared statement helper

Per rendere le query PDO più brevi e sicure, puoi usare:

function pdo_fetch_all($conn, $sql, $params=[]) {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


Uso:

$sql = "SELECT * FROM `".DB::$pref."lay` WHERE TRIM(lstat)='' AND ltmp=?";
$rows = pdo_fetch_all($conn, $sql, [$tema]);


Non devi scrivere sempre prepare/execute/fetchAll

Sicuro contro SQL injection

Chiaro e leggibile

🔹 Come si integrano

Puoi mettere tutte queste funzioni in un file, ad esempio helpers.php, e poi:

require_once('helpers.php');


…e le hai disponibili in tutti i tuoi script.
