<<?php
// Ottieni l'istanza e la connessione PDO
$db = Database::getInstance();
$conn = $db->getConnection();

// Esegui una query di esempio
$sql = "SELECT * FROM utenti";
$stmt = $conn->prepare($sql);
$stmt->execute();

// Recupera i risultati
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($rows) > 0) {
    foreach ($rows as $row) {
        echo "ID: " . $row["id"] . " - Nome: " . $row["nome"] . "<br>";
    }
} else {
    echo "Nessun risultato trovato!";
}

/**
Come funziona:

La prima volta che chiami 
    Database::getInstance(), viene creata una nuova istanza della classe Database e viene stabilita 
        una connessione al database.
Le chiamate successive a 
    Database::getInstance() restituiranno la stessa istanza della classe, quindi la connessione 
        al database è unica e riutilizzabile in tutta l'applicazione.*/
?>