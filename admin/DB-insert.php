<?php
$dsn = "mysql:host=localhost;dbname=my_database;charset=UTF-8mb4";
$user = "root";
$pass = "";

$pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]);

// NOME TABELLA DA USARE
$tabella = "prefix_art";

// PRENDE I NOMI DEI CAMPI DAL DATABASE
$colonne = [];
$stmt = $pdo->query("DESCRIBE $tabella");
foreach ($stmt as $riga) {
    if ($riga['Extra'] !== 'auto_increment') {  // esclude l'id
        $colonne[] = $riga['Field'];
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Prepara array dati prendendo SOLO i campi presenti in tabella
    $dati = [];
    foreach ($colonne as $c) {
        if (isset($_POST[$c])) {
            $dati[$c] = $_POST[$c];
        }
    }

    // Crea lista colonne e placeholder
    $placeholders = array_map(fn($c) => ":$c", $colonne);

    // Costruisce la query dinamica
    $sql = "INSERT INTO $tabella (" . implode(", ", $colonne) . ")
            VALUES (" . implode(", ", $placeholders) . ")";

    // Prepara ed esegue
    $stmt = $pdo->prepare($sql);
    $stmt->execute($dati);

    echo "Record inserito con successo!";
}
?>
<!DOCTYPE html>
<html lang="it">
<head>

<meta charset="UTF-8">
<title>Insert Automatico</title>
</head>
<body>

<h1>Inserimento automatico nella tabella: <?= $tabella ?></h1>

<form method="post">

<?php foreach ($colonne as $c): ?>
    <?= ucfirst($c) ?>:<br>
    <input type="text" name="<?= $c ?>" required><br><br>
<?php endforeach; ?>

<button type="submit">Salva</button>

</form>

</body>
</html>
