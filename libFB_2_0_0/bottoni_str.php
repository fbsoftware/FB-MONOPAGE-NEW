<?php

class bottoni_str         extends bottoni
{                                                 
  public $titolo   =  '';      // titolo
  public $tabella  =  '';      // nome archivio
  
        public function __construct($titolo,$tabella)       
               { $this->titolo = $titolo;
                 $this->tabella = $tabella;}  
                           
        function bt_gest()           //  bottoni gestione
                {      
                echo    "<div class='azioni'>";
                echo    "<div class='titolo'>";
                // titolo e bottoni 
                echo "<img src='images/$this->tabella.png' alt='manca img' height='50'> ";
                echo    "&nbsp;$this->titolo</div>";
                echo    "<div class='bottoni'>";
                echo    "<form method='post' id='$this->tabella' action='upd_$this->tabella.php'>" ;
                $bt1  = new bottoni('ins');         $bt1->bt_nuovo();
                $bt2  = new bottoni('mod');         $bt2->bt_modifica();
                $bt3  = new bottoni('canc');        $bt3->bt_cancella();
                $bt5  = new bottoni('chiudi');      $bt5->bt_chiudi();                
                echo    "</div>";
                echo    "</div>"; 
                }
                           
        function jbt_gest()           //  bottoni gestione per jquery
                {      
                echo    "<div class='azioni'>";
                echo    "<div class='titolo'>";
                // titolo e bottoni
                echo "<img src='images/$this->tabella.png' alt='manca img' height='50'> ";
                echo    "&nbsp;$this->titolo</div>";
                echo    "<div class='bottoni'>";
                echo    "<form method='post' action='jupd_$this->tabella.php' id='form_$this->tabella'>" ;
                $bt1  = new bottoni('ins');         $bt1->bt_nuovo();
                $bt2  = new bottoni('mod');         $bt2->bt_modifica();
                $bt3  = new bottoni('canc');        $bt3->bt_cancella();
                $bt5  = new bottoni('chiudi');      $bt5->bt_chiudi();                
                echo    "</div>";
                echo    "</div>"; 
                }
               
               
        function bt_upd_ins()           //  bottoni inserimento
                {                          
                echo    "<br ><div class='azioni'>";
                echo    "<div class='titolo'>";
                echo "<img src='images/$this->tabella.png' alt='manca img' height='50'> ";
                echo    "$this->titolo</div>";
                echo    "<div class='bottoni'>";
                echo    "<form class='bottoni' method='post' action='write_$this->tabella.php'>" ;
                $bt1 = new bottoni('ins');       $bt1->bt_salva();
                $bt2 = new bottoni('uscita');    $bt2->bt_uscita();
                echo    "</div>";
                echo    "</div>";
                }  
               
        function bt_upd_mod()           //  bottoni modifica
                {                          
                 echo    "<br ><div class='azioni'>";
                 echo    "<div class='titolo'>";
                 echo    "<img src='images/$this->tabella.png' alt='manca img' height='50'> ";
                 echo    "$this->titolo</div>";
                 echo    "<div class='bottoni'>";
                 echo    "<form class='bottoni' method='post' action='write_$this->tabella.php'>" ;
                 $bt1 = new bottoni('mod');       $bt1->bt_salva();
                 $bt2 = new bottoni('uscita');    $bt2->bt_uscita();
                 echo    "</div>";
                 echo    "</div>"; 
                }  
        function bt_upd_canc()           //  bottoni conferma cancellazione
                {  
                echo    "<br ><div class='azioni'>";
                echo    "<div class='titolo'>";
                echo    "<img src='images/$this->tabella.png' alt='manca img' height='50'> ";
                echo    "$this->titolo</div>";
                echo    "<div class='bottoni'>";
                echo    "<form method='post' method='post' action='write_$this->tabella.php'>" ;
                $bt1 = new bottoni('canc');      $bt1->bt_cancella();
                $bt2 = new bottoni('uscita');    $bt2->bt_uscita();
                echo    "</div>";
                echo    "</div>"; 
                }  
        function bt_esci()           //  bottone chiudi
                {  
                echo    "<br ><div class='azioni'>";
                echo    "<div class='titolo'>$this->titolo</div>";
                echo    "<div class='bottoni'>";
                echo    "<form method='post' action='upd_$this->tabella.php'>" ;
                $bt1 = new bottoni('chiudi');      $bt1->bt_chiudi();
                echo    "</div>";
                echo    "</div>"; 
                } 
                 
     function bt_info()           //  bottone info
                {  
                echo    "<br ><div class='azioni'>";
                echo    "<div class='titolo'>$this->titolo</div>";
                echo    "<div class='bottoni'>";
                echo    "<form method='post' action='upd_$this->tabella.php'>" ;
                $bt1 = new bottoni('chiudi');      $bt1->bt_chiudi();
                echo    "</div>";
                echo    "</div>"; 
                }  
               
        function bt_upd_ins_voce()   //  bottoni inserimento con scelta tipo
                {                    //  action = upd2 !-importante      
                echo    "<br ><div class='azioni'>";
                echo    "<div class='titolo'>$this->titolo</div>";
                echo    "<div class='bottoni'>";
                echo    "<form method='post' action='upd2_$this->tabella.php'>" ;
                $bt1 = new bottoni('ins');       $bt1->bt_salva();
                $bt2 = new bottoni('uscita');    $bt2->bt_uscita();
                echo    "</div>";
                echo    "</div>";
                }  
               
        function bt_upd_ins_tipomod()   //  bottoni inserimento con scelta tipo
                {                    //  action = upd2 !-importante      
                echo    "<br ><div class='azioni'>";
                echo    "<div class='titolo'>$this->titolo</div>";
                echo    "<div class='bottoni'>";
                echo    "<form method='post' action='upd2_$this->tabella.php'>" ;
                $bt1 = new bottoni('ins');       $bt1->bt_salva();
                $bt2 = new bottoni('uscita');    $bt2->bt_uscita();
                echo    "</div>";
                echo    "</div>";
                }  
                 
     function bt_upload()           //  bottone upload locale
                {  
                echo    "<br ><div class='azioni'>";
                echo    "<div class='titolo'>";
                echo    "<img src='images/up.jpg' alt='upld' height='50'>&nbsp;&nbsp;$this->titolo ";
                echo    "</div>
                        <div class='bottoni'>";
                echo    "<form action='upload1_tmp.php' name='upload' enctype=multipart/form-data method='post'>" ;
                $bt1 = new bottoni('upload');      $bt1->bt_upld();
                $bt2 = new bottoni('uscita');      $bt2->bt_uscita();
                echo    "</div></div>";
                }  
     function bt_upload_ftp()           //  bottone upload ftp
                {  
                echo     "<br ><div class='azioni'>";
                echo     "<div class='titolo'>";
                echo     "<img src='images/template.jpg' alt='upld' height='50'>&nbsp;&nbsp;$this->titolo ";
                echo     "</div>";
                echo     "<div class='bottoni'>";
                echo     "<form class='bottoni' action='admin.php'>";
                 $bt2 = new bottoni('uscita');    $bt2->bt_uscita();                
                echo     "</form>"; 
                echo     "<form class='bottoni' action='write_ftp.php' name='upload' 
                              method='post' enctype='multipart/form-data'>" ;
                         $bt1 = new bottoni('upload');          $bt1->bt_upld();
                 echo    "</div>";
                 echo    "</div>"; 

                }  

     function bt_upload_media()           //  bottone upload immagini    (usato ???)
                {  
                echo     "<div class='azioni'>";
                echo     "<div class='titolo'>";
                echo     "<img src='images/up.jpg' alt='upld' height='50'>&nbsp;&nbsp;$this->titolo ";
                echo     "</div>";
                echo     "<div class='bottoni'>";
                echo     "<form class='bottoni' method='post' action='write_$this->tabella.php'>";                
                         $bt2 = new bottoni('uscita');    $bt2->bt_uscita();                
                echo     "</form>"; 
                echo     "<form class='bottoni' action='write_$this->tabella.php' name='upload'    
                              method='post' enctype='multipart/form-data'>" ;
                         $bt1 = new bottoni('upload');          $bt1->bt_upld();
                echo     "</div>";
                echo     "</div>"; 


                }  
     function bt_download_media()           //  bottone download immagini    (usato ???)
                {  
                echo     "<div class='azioni'>";
                echo     "<div class='titolo'>";
                echo     "<img src='images/dwn.jpg' alt='dwnld' height='50'>&nbsp;&nbsp;$this->titolo ";
                echo     "</div>";
                echo     "<div class='bottoni'>";
                echo     "<form class='bottoni' method='post' action='write_$this->tabella.php'>";                
                         $bt2 = new bottoni('uscita');    $bt2->bt_uscita();                
                echo     "</form>"; 
                echo     "<form class='bottoni' action='write_$this->tabella.php' name='upload'    
                              method='post' enctype='multipart/form-data'>" ;
                         $bt1 = new bottoni('download');          $bt1->bt_dwnld();
                echo     "</div>";
                echo     "</div>"; 

                }  


        function bt_media()           //  bottoni upload e chiudi
                {  
                echo    "<div class='azioni'>";
                echo    "<div class='titolo'><img src='images/img.png' alt='upld' height='50'>$this->titolo</div>";
                echo    "<div class='bottoni'>";
                echo    "<form method='post' action='upd_$this->tabella.php'>" ;
                $bt2 = new bottoni('upload');          $bt2->bt_upld();                
                $bt1 = new bottoni('chiudi');          $bt1->bt_chiudi();
                echo    "</div>";
                echo    "</div>"; 
                } 
               
        function bt_upd_config()           //  bottoni modifica  configurazione
                {                          
                 echo    "<br ><div class='azioni'>";
                 echo    "<div class='titolo'><img src='images/tool.png' alt='upld' height='50'>&nbsp;&nbsp;$this->titolo</div>";
                 echo    "<div class='bottoni'>";
                 echo    "<form class='bottoni' action='admin.php'>";
                 $bt2 = new bottoni('uscita');    $bt2->bt_uscita();                
                 echo    "</form>"; 
                 echo    "<form class='bottoni' method='post' action='write_$this->tabella.php'>" ;
                 $bt1 = new bottoni('mod');       $bt1->bt_salva();
                 echo    "</div>";
                 echo    "</div>"; 
                }  
               
        function bt_db_tab()           //  scelta tabella DB
                {                          
                 echo    "<br ><div class='azioni'>";
                 echo    "<div class='titolo'>
                          <img src='images/Help 3.png' alt='manca img' height='50'>
                          $this->titolo</div>";
                 echo    "<div class='bottoni'>";
                 echo    "<form class='bottoni' action='db_gest_new.php'>";    // ??? //            
                 $bt2 = new bottoni('uscita');    $bt2->bt_uscita();                
                 echo    "</form>"; 
                 echo    "<form class='bottoni' method='post' action='gest_tabella.php'>" ;
                 $bt1 = new bottoni('tab');       $bt1->bt_salva();
                 echo    "</div>";
                 echo    "</div>"; 
                }  
                      
                
                
                
  } 
class bottoni      
{                                                 
  public $action  =  '';
  
        public function __construct($action)       
               { 
               $this->action = $action;
               }
                       
        public function bt_nuovo()     // nuovo  
          {       require(' ');
          echo    "<button class='big' type='submit' name='submit' value='$this->action' id='$this->action'>
                  <img src='images/new_f1.png' height='25'/>$NEW</button>";
                  }
        public function bt_help()     // help   
          {       //require(' ');
          echo    "<a href='help00.php?file_h=testi/".$this->action.".txt' target='_self'>
                  <button class='big' type='button' id='$this->action'>
                  <img src='images/con_info.png' height='25'/>Aiuto</button></a>";
                  }          
        public function bt_salva()     // salva  
          {       require(' ');
          echo    "<button class='big' type='submit' name='submit' value='$this->action' id='$this->action'>        
                   <img src='images/save_f2.png' height='25'/>$SAV</button>";
                  }          
        public function bt_uscita()     // uscita  
          {       require(' ');
          echo    "<button class='big' type='submit' name='submit' value='$this->action' id='$this->action'>
                  <img src='images/cancel_f2.png' height='28'/>$USC</button>";
                    $_SESSION['esito'] = 2;
                  }          
        public function bt_modifica()     // modifica  
          {       require(' ');
          echo    "<button class='big' type='submit' name='submit' value='$this->action' id='$this->action'>
                  <img src='images/edit_f2.png' height='25'/>$MOD</button>";
                  }
        public function bt_cancella()     // cancella  
          {       require(' ');
          echo    "<button class='big' type='submit' name='submit' value='$this->action' id='$this->action'>
                  <img src='images/delete.png' height='25'/>$DEL</button>";
                  } 
        public function bt_chiudi()     // chiusura mappa  
          {       require(' ');
          echo    "<button class='big' type='submit' name='submit' value='$this->action' id='$this->action'>
                  <img src='images/close.jpg' height='25'/>$CLO</button>";
                  } 
        public function bt_upld()     // upload files  
          {       //require(' ');
          echo    "<button class='big' type='submit' name='submit' value='$this->action' id='$this->action'>
                  <img src='images/upld.png' height='25'/>Upload</button>";
                  } 
        public function bt_dwnld()     // download files  
          {       //require(' ');
          echo    "<button class='big' type='submit' name='submit' value='$this->action' id='$this->action'>
                  <img src='images/down.png' height='25'/>Download</button>";
                  } 

        public function bt_email()     // email  
          {       //require(' ');
          echo    "<button class='big' type='submit' name='submit' value='$this->action' id='$this->action'>        
                   <img src='images/email_2.png' height='25'/>Invia</button>";
                  }          
        public function bt_reset()     // reset form 
          {       //require(' ');
          echo    "<button class='big' type='reset' name='reset' value='$this->action' id='$this->action'>        
                   <img src='administrator/images/cancel_f2.png' height='25'/>Resetta</button>";
                  } 
  }     
?>