<?php
/**========================================================================== 
  imgdim.php                                                                        
  Classe per il rilevare la maggiore fra altezza(A) e larghezza(L)
============================================================================= */
class img_dim            
{                                                 
     public $img    =  '';
     public $max    =  '';
   
        public function __construct($img)       
               {    $this->img     = $img;
					$this->max     = $max;}  
                       
        public function maxdim()       
          {
          $pathimg = $this->img;
          $dim=array();
          $dim = getimagesize($pathimg);
          $x=$dim['0'];   
          $y=$dim['1'];   
          // nei limiti entrambe le dimensioni 
               if($x == $y) 
                         {
                         $this->max = "A";
                         }
               elseif  ($x > $y) 
                         {
                         $this->max = "A";
                         }
               elseif  ($x < $y) 
                         {
                         $this->max = "L";
                         }
			 return $this->max;
          }
}

?>