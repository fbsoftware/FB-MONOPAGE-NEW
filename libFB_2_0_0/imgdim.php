
<?php
/**==========================================================================
  imgdim.php
  Classe per il dimensionamento delle immagini in modo da adeguarle alla
  finestra che le ospita. Se l'immagine è più piccola in entrambe le dimensioni,
  viene visualizzata al naturale.(30/11/2021)
============================================================================= */
class imgdim
{
     public $img    =  '';
     public $alt    =  '';
     public $lun    =  '';

     public $width  =  '';
     public $height =  '';
     public $imgh =  '';
     public $imgl =  '';

        public function __construct($img,$alt,$lun)
               {    $this->img     = $img;
                    $this->alt     = $alt;
                    $this->lun     = $lun;
               }

        public function maxdim()
          {
          $pathimg = ltrim($this->img);
          $dim=array();
          $dim = getimagesize($pathimg);
          $x=$dim['0'];   $this->imgl = $x;
          $y=$dim['1'];   $this->imga = $y;

          // nei limiti entrambe le dimensioni
               if (($this->imgl <= $this->lun) && ($this->imga <= $this->alt))
                         {
                         echo "<img class='fb-imgdim' src='$this->img' width='$this->imgl' alt='$this->imga' border='1' />";
                         }
               else      {
                         $vx = $this->lun/$this->imgl;
                         $vy = $this->alt/$this->imga;

                         // adeguo la + critica fra le dimensioni
                         if($vx < $vy)  {$this->width = $this->lun;
                              echo "<img class='fb-imgdim' src='$this->img' width='$this->lun' alt='$this->img' border='1' />"; }
                           else     {      $this->height= $this->alt;
                              echo "<img class='fb-imgdim' src='$this->img' height='$this->alt' alt='$this->img' border='1' />";}
                          }
          }
}

?>
