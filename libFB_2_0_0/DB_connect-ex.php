<?php
/**
 Connessione al database
 */
class DB_connect

{

  function connect()
  {
    try
    {
    $PDO = new PDO("mysql:host=".DB::$host.";dbname=".DB::$db."",DB::$user,DB::$pw, array(
        PDO::ATTR_PERSISTENT => true));
      $PDO->beginTransaction();
    }
    catch(PDOException $e)
    { echo $e->getMessage(); }
  }
}

?>
