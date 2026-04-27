<?php
/**
 Connessione al database - versione aggiornata per UTF-8/UTF-8mb4 e PDO sicuro
*/
class DB_connect
{
    /**
     * Crea e ritorna una connessione PDO configurata per UTF-8mb4.
     * Mantengo beginTransaction() per compatibilità con il comportamento precedente,
     * se preferisci puoi rimuoverlo e avviare la transazione dove serve.
     *
     * Usage:
     *   $db = new DB_connect();
     *   $PDO = $db->connect();
     *
     * @return PDO
     * @throws PDOException
     */
    public function connect()
    {
        try {
            $dsn = "mysql:host=" . DB::$host . ";dbname=" . DB::$db . ";charset=UTF-8mb4";

            $options = [
                PDO::ATTR_PERSISTENT         => true,
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $PDO = new PDO($dsn, DB::$user, DB::$pw, $options);

            // Forziamo l'uso di UTF-8mb4 anche lato sessione (utile su alcune configurazioni)
            $PDO->exec("SET NAMES 'UTF-8mb4' COLLATE 'UTF-8mb4_unicode_500_ci'");

            // Se vuoi che la transazione sia aperta qui (comportamento originale)
            $PDO->beginTransaction();

            return $PDO;
        } catch (PDOException $e) {
            // Non stampare messaggi sensibili in produzione; loggare invece
            error_log("DB_connect error: " . $e->getMessage());
            throw $e; // o die("Errore connessione al DB."); a seconda del flusso che desideri
        }
    }
}
/** 
Note rapide
Questa versione imposta charset=UTF-8mb4 nel DSN e invia anche SET NAMES UTF-8mb4; così il client e server usano 
correttamente UTF‑8 a 4 byte.
Ritorna l'oggetto PDO (prima veniva creato ma non ritornato) quindi ricordati di assegnarlo:
$db = new DB_connect(); $PDO = $db->connect();
Ho aggiunto opzioni consigliate: ERRMODE_EXCEPTION, FETCH_ASSOC e EMULATE_PREPARES = false (usa prepared 
statements nativi).
Ho lasciato PDO::ATTR_PERSISTENT = true perché era nel tuo file originale; se hai problemi o non vuoi connessioni 
persistenti rimuovi quella riga.
Dopo aver aggiornato la connessione, assicurati di rimuovere eventuali UTF-8_decode() nel tuo codice (come nei 
file DB_nuovo/DB_modifica) — quei decode spesso causano la perdita di accenti/apostrofi quando si usa UTF‑8 
correttamente.
Se vuoi, posso anche fornirti una versione con metodo statico (DB_connect::connect()) o modificare init_admin.php 
per creare la connessione centrale $PDO automaticamente.
*/
?>