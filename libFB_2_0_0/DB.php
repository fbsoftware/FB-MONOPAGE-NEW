<?php
/* =========================================================================
	19/5/20	aggiunto DB::$ROOT = root assoluta, cioè localhost/...
   =======================================================================*/

class DB
{      
        public static $con  = '';         // collegamento  
        public static $host = '';         // *host
        public static $user = '';         // *utente
        public static $db   = '';         // *database
        public static $pw   = '';         // *password
        public static $pref = '';         // *prefisso archivi
        public static $incr = 0;          // *incremento per inserimento  
        public static $root = '';         // *root - sito
        public static $site = '';         // *nome del sito
        public static $sep = '';          // *separatore directory
        public static $dir_imm = '';      // *directory immagini
        public static $page_title = '';   // *titolo del sito
        public static $e_mail = '';       // *e-mail relativa al sito       
        public static $url = '';          // *url del sito (http://....)
        public static $livello = '';      // *livello di versione
        public static $modify= '';     	  // *modifica di versione
        public static $rilascio = '';     // *rilascio di versione
        public static $author = '';       // *autore
        public static $keywords = '';     // *parole chiave
        public static $lib = '';          // *libreria standard
        public static $ambiente  =  '';	  // ambiente: sito,admin
//        public $mod_ins = 0;            // progressivo per inserimento moduli
        public $max = 0;                  // nuovo inserimento
	public static $ROOT = '';         // ROOT assoluta = localhost/...
  
        public function __construct() 
		
          {
			  $filename = "config.ini";
			  if (file_exists($filename)) {
			  	$arr = parse_ini_file ("config.ini" , true );}
			 else {
				$arr = parse_ini_file ("../config.ini" , true );}
                  self::$livello   = $arr['versione']['livello'];
                  self::$rilascio  = $arr['versione']['rilascio'];
                  self::$modify    = $arr['versione']['modify'];

                  self::$host      = $arr['DB']['host'];
                  self::$user      = $arr['DB']['user'];
                  self::$pw        = $arr['DB']['pw'];
                  self::$db        = $arr['DB']['db'];
                  self::$pref      = $arr['DB']['pref'];
				  
				  self::$root       = $arr['config']['root'];
                  self::$site       = $arr['config']['site'];
	              self::$page_title = $arr['config']['page_title'];
                  self::$dir_imm    = $arr['config']['dir_imm'];
				  self::$lib        = $arr['config']['lib'];
                  self::$incr       = $arr['config']['incr'];
                  self::$sep        = $arr['config']['sep'];
                  self::$e_mail     = $arr['config']['e_mail'];
                  self::$url        = $arr['config']['url'];
                  self::$author     = $arr['config']['author'];
                  self::$keywords   = $arr['config']['keywords'];
		self::$ROOT       = $_SERVER['DOCUMENT_ROOT'].self::$root;
          return $arr;
         }
				  
// destruct        
         public function __destruct()      // chiusura DB e connessione
        {   $con = NULL;
        }
}

?>