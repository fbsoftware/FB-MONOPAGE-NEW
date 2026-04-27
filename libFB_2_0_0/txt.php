<?php
/**===========================================================================
   * Classe per la gestione degli inglobamenti di HTML specifico nei testi
   * per gestire:
   * - video YpuTube
   * - gallery Picasa
   * - testo scorrevole
   * - select a scelta singola
   * - select a scelta multipla
   * - calendario Google
   * - mappa Google (+ 31/10/2014)
   * - indicatore ShinyStat
   * - spezzone di html
=============================================================================*/
class txt
     {
     public $text ='';          // testo da trattare
     public $testo=array();     // array interna

        public function __construct($text)
               {
               $this->testo  = $text;
               }

        public function ingloba()
        {
          $testo = $this->testo  ;
          $this->testo=explode('{media}',$this->testo);
          $i=0;
          for ($i=0; $i < count($this->testo) ;$i++ )
          {
// YouTube
               if (substr($this->testo[$i],0,8) == 'youtube|')
               { echo "<br />";
               $codvid=array();
               $testoi=$this->testo[$i];
               list($codvid,$width,$height)=explode('|',substr($this->testo[$i],8));
               if ($width  <= 0)   $width=480;
               if ($height <= 0)   $height=360;
               echo "<div id='wrapp'>
               <iframe id='player'  src='https://www.youtube.com/embed/".$codvid."' 
			    type='text/html' 
			   width='".$width."' 
			   height='".$height."' 
			   frameborder='0' allowfullscreen='0'>
               </iframe></div><br />";
               }
// gallery Picasa
                elseif (substr($this->testo[$i],0,7) == 'picasa|')
               {echo "<br />";
               $codvid=array();
               $testoi=$this->testo[$i];
               list($coduser,$codgal,$width,$height)=explode('|',substr($this->testo[$i],7));
               if ( $width == 0) $width=400;
               if ( $heigh == 0) $heigh=300;
               $rgb="0x".TMP::$tcolchi;    if ( $rgb == '0x      ')  $rgb='0xffffff';
               echo  "<embed type='application/x-shockwave-flash' ";
               echo  "src='https://picasaweb.google.com/s/c/bin/slideshow.swf'";
               echo  "width='".$width."'";
               echo  "height='".$heigh."'";
               echo  "flashvars='host=picasaweb.google.com&captions=1&noautoplay=1&hl=it&feat=flashalbum&RGB=".$rgb."&feed=https%3A%2F%2Fpicasaweb.google.com%2Fdata%2Ffeed%2Fapi%2Fuser%2F".$coduser."%2Falbumid%2F".$codgal."%3Falt%3Drss%26kind%3Dphoto%26hl%3Dit' ";
               echo  "pluginspage='http://www.macromedia.com/go/getflashplayer'></embed>";
               }
// marquee
               elseif (substr($this->testo[$i],0,8) == 'marquee|')
               {
               echo "<br />";
               list($marq_text)=explode('|',substr($this->testo[$i],8));
               echo "<marquee scrollamount=3 scrolldelay=9 >$marq_text</marquee><br />";
               }
// select
               elseif (substr($this->testo[$i],0,7) == 'select|')
               {
               echo "<br />";
               $select_text=array();
               $testoi=$this->testo[$i];
               echo "<select>";
               $select_text=explode('|',substr($this->testo[$i],7));
               $conta = count($select_text);
               for($x=0; $x<$conta; $x++)
               {
               echo "<option value='$select_text[$x]'>$select_text[$x]</option>";
               }
               echo "</select><br />";
               }
// select a scelte multiple
               elseif (substr($this->testo[$i],0,8) == 'selectm|')
               {
               echo "<br />";
               $select_text=array();
               $testoi=$this->testo[$i];
               echo "<select multiple='multiple'>";
               $select_text=explode('|',substr($this->testo[$i],8));
               $conta = count($select_text);
               for($x=0; $x<$conta; $x++)
               {
               echo "<option value='$select_text[$x]'>$select_text[$x]</option>";
               }
               echo "</select><br />";
               }
// calendario Google
               elseif (substr($this->testo[$i],0,12) == 'calengoogle|')
               {
               $calen = "<iframe src='https://www.google.com/calendar/embed?title=Calendario%20eventi%202014&amp;height=375&amp;wkst=1&amp;hl=it&amp;bgcolor=%23ff6666&amp;src=gbnh153mq19ahltfdsj9t6788s%40group.calendar.google.com&amp;color=%23711616&amp;ctz=Europe%2FRome' style=' border:solid 1px #777 ' width='490' height='375' frameborder='0' scrolling='no'></iframe>
               ";
               $this->testo[$i] = $calen;
               echo "<br />".$this->testo[$i];
                }
// mappa Google
               elseif (substr($this->testo[$i],0,12) == 'mappagoogle|')
               {
               $codvid=array();
               $testoi=$this->testo[$i];
               list($indir,$luogo)=explode('|',substr($this->testo[$i],12));
               $map = "<iframe   width='180'    height='180' frameborder='0' style='border:0'
                    src='https://www.google.com/maps/embed/v1/place?key=AIzaSyDVQgfYwTz6wN0SlnTttmAuNJRz69270Fg
                    &q=".$indir.",".$luogo."></iframe>";
               $this->testo[$i] = $map;
               echo "<br />".$this->testo[$i];
                }
// ShinyStat
               elseif (substr($this->testo[$i],0,10) == 'shinystat|')
               {
               $shiny = "<!-- Begin Shinystat code -->
               <script type='text/javascript' src='http://codice.shinystat.com/cgi-bin/getcod.cgi?USER=fbsoftware'></script>
               <noscript>
               <a href='http://www.shinystat.com' target='_top'>
               <img src='http://www.shinystat.com/cgi-bin/shinystat.cgi?USER=fbsoftware' alt='Free hit counters' border='0' /></a>
               </noscript>
               <!-- End Shinystat code -->
               ";
               $this->testo[$i] = $shiny;
               echo "<br />".$this->testo[$i];
               }
// html
               elseif (substr($testo[$i],0,5) == 'html|')
               {
               $codvid=array();
               $testoi=$testo[$i];
               require substr($testo[$i],5,25);
               }

// altrimenti mantengo il testo originario
               else
               {
               echo $this->testo[$i];
               }
          }
     }
     }
?>