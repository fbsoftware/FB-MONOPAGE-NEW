<?php
/**
 *
 */
class loadTemplate
{
public $ambiente="";
  function __construct($ambiente)
  {
    $this->ambiente = $ambiente;
  }
  public function loadTmp()
  {
    $DB  = new DB();
    $tmp = new TMP($this->ambiente);
    $tmp->read_tmp();
  }
}

?>
