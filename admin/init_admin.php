<?php
/**
=================================================
init_admin.php - inizializzazione lato admin
=================================================*/
// Impostazioni base PHP
ini_set('default_charset', 'UTF-8');
header('Content-Type: text/html; charset=UTF-8');
// librerie e head 
require_once('loadLibraries.php');
$app = new Head('Amministratore');
$app->openHead();
require_once("jquery_linkAdmin.php");
require_once("include_head.php");
$app->closeHead();
?>