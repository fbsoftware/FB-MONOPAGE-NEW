<?php
/**
 *Class FileIni
 *This class can be used to read and write in ini files
 *@Author Josiel A. Santos <josiel_lkp@yahoo.com.br>
 *@Version 1.0
**/
class FileIni
{
    /**
     *Property $filename
     *The name of file loaded
     *@Var string
     *@Since Version 1.0
     *@Access protected
    **/
    protected $filename;

    /**
     *Property $content
     *This property holds the contents of the ini file as an associative array
     *@Var array
     *@Since Version 1.0
     *@Access protected
    **/
    protected $content=array();

    /**
     *Property $config
     *Holds settings to manipulate the file
     *@Var array
     *@Since Version 1.0
     *@Access protected
    **/
    protected $config=array();

    /**
     *Function __construct
     *Constructor of this class
     *@Param string $filename Name of the file to manipulate
     *@Param array $options [optional] The options to manipulates the ini file
     *@Since Version 1.0
     *@Access public
    **/
    public function __construct($filename, $options=array())
    {
        $filename=trim((string)$filename);
        if(!is_file($filename)) 
        {
            trigger_error("[Class FileIni]: File .ini non trovato-1", E_USER_WARNING);
            exit();
        }
        if(!file_exists($filename))
        {
            trigger_error("[Class FileIni]: File .ini non trovato-2", E_USER_WARNING);
            exit();
        }
        if(!is_readable($filename)) 
        {
            trigger_error("[Class FileIni]: File .ini non leggibile", E_USER_WARNING);
            exit();
        }
        if(!is_writable($filename))
        {
            trigger_error("[Class FileIni]: File .ini non scrivibile", E_USER_WARNING);
            exit();
        }                
        
        
        
        
 $filename;        
        $this->filename = $filename;
        $this->content = parse_ini_file($this->filename, true);
        if(!$this->content)
        {
            trigger_error("[Class FileIni]: Parse non eseguibile per il file", E_USER_WARNING);
            exit();
        }
        $this->setConfig($options);
    }

    /**
     *Function sectionExists
     *Verify the existence of a section
     *@Param string $sec Name of the section to verify
     *@Return boolean true if exists, false if not exists
     *@Since Version 1.0
     *@Access public
    **/
    public function sectionExists($sec)
    {
        $sec=trim((string)$sec);
        return (array_key_exists($sec, $this->content) && is_array($this->content[$sec]))?true:false;
    }

    /**
     *Function keyExists
     *Verify the existence of a key in a section
     *@Param string $sec Identifier of the section
     *@Param string $key Identifier of the key
     *@Return boolean true if exists, false if not exists
     *@Since Version 1.0
     *@Access public
    **/
    public function keyExists($sec, $key)
    {
        $sec=trim((string)$sec);
        $key=trim((string)$key);
        if(!$this->sectionExists($sec))
        {
            trigger_error("[Class FileIni]: La sezione '" . $sec . "' non esiste", E_USER_ERROR);
            return false;
        }
        return (array_key_exists($key, $this->content[$sec]))?true:false;
    }

    /**
     *Function getKeysFromSection
     *Gets a list of keys of a section
     *@Param string $sec Name of the section
     *@Return array The list of keys, false if error
     *@Since Version 1.0
     *@Access public
    **/
    public function getKeysFromSection($sec)
    {
        $sec=trim((string)$sec);
        if(!$this->sectionExists($sec))
        {
            trigger_error("[Class FileIni]: La sezione '" . $sec . "' non esiste", E_USER_ERROR);
            return false;
        }
        return array_keys($this->content[$sec]);
    }

    /**
     *Function getValuesFromSection
     *Gets the values of a section
     *@Param string Name of the section
     *@Return array The values of section, false if error
     *@Since Version 1.0
     *@Access public
    **/
    public function getValuesFromSection($sec)
    {
        $sec=trim((string)$sec);
        if(!$this->sectionExists($sec))
        {
            trigger_error("[Class FileIni]: La sezione '" . $sec . "' non esiste", E_USER_ERROR);
            return false;
        }
        return array_values($this->content[$sec]);
    }

    /**
     *Function getItensFromSection
     *Gets the itens of a section
     *@Param string $sec Identifier of the section
     *@Return array The itens of the section (false if error)
     *@Since Version 1.0
     *@Access public
    **/
    public function getItensFromSection($sec)
    {
        $sec=trim((string)$sec);
        if(!$this->sectionExists($sec))
        {
            trigger_error("[Class FileIni]: La sezione '" . $sec . "' non esiste", E_USER_ERROR);
            return false;
        }
        $itens=array();
        foreach($this->content[$sec] as $key=>$value)
        {
            $itens[]=$key . "=" . $value;
        }
        return $itens;
    }

    /**
     *Function getSections
     *Gets a list of sections
     *@Return array A list of sections
     *@Since Version 1.0
     *@Access public
    **/
    public function getSections()
    {
        return array_keys($this->content);
    }

    /**
     *Function getValue
     *Gets a value of a key of a section
     *@Param string $sec Name of the section
     *@Param string $key Name of the key
     *@Return mixed The value
     *@Since Version 1.0
     *@Access public
    **/
    public function getValue($sec, $key)
    {
        $sec=trim((string)$sec);
        $key=trim((string)$key);
        if(!$this->keyExists($sec, $key))
        {
            trigger_error("[Class FileIni]: La variabile '" . $key . "' non esiste", E_USER_ERROR);
            return false;
        }
        return $this->content[$sec][$key];
    }

    /**
     *Function deleteKey
     *Deletes a key of a section
     *@Param string $sec Identifier of the section
     *@Param string $key Identifier of the key
     *@Return boolean true if deleted, false if not deleted
     *@Since Version 1.0
     *@Access public
    **/
    public function deleteKey($sec, $key)
    {
        if($this->config['readOnly']===true)
        {
            trigger_error("[Class FileIni]: Non posso modificare il file perchè read only", E_USER_ERROR);
            return false;
        }
        $sec=trim((string)$sec);
        $key=trim((string)$key);
        if(!$this->keyExists($sec, $key))
        {
            trigger_error("[Class FileIni]: La voce '" . $key . "' non esiste", E_USER_ERROR);
            return false;
        }
        $pos=array_search($key, array_keys($this->content[$sec]), true);
        array_splice($this->content[$sec], $pos, 1);
        if($this->config['autoSave']===true)
        {
            $this->save();
        }
        return true;
    }

    /**
     *Function deleteSection
     *Deletes a section
     *@Param string $sec Name of the section
     *@Return boolean true if deleted, false if not deleted
     *@Since Version 1.0
     *@Access public
    **/
    public function deleteSection($sec)
    {
        if($this->config['readOnly']===true)
        {
            trigger_error("[Class FileIni]: Non posso modificare il file perchè read only", E_USER_ERROR);
            return false;
        }
        $sec=trim((string)$sec);
        if(!$this->sectionExists($sec))
        {
            trigger_error("[Class FileIni]: La sezione '" . $sec . "' non esiste", E_USER_ERROR);
            return false;
        }
        $pos=array_search($sec, array_keys($this->content), true);
        array_splice($this->content, $pos, 1);
        if($this->config['autoSave']===true)
        {
            $this->save();
        }
        return true;
    }

    /**
     *Function setValue
     *Setes a value
     *@Param $sec Name of a section
     *@Param string Name of a key
     *@Param mixed The value
     *@Return mixed The value
     *@Since Version 1.0
     *@Access public
    **/
    public function setValue($sec, $key, $value)
    {
        if($this->config['readOnly']===true)
        {
            trigger_error("[Class FileIni]: Non posso modificare il file perchè read only", E_USER_ERROR);
            return false;
        }
        $sec=trim((string)$sec);
        $key=trim((string)$key);
        $value=trim((string)$value);
        $this->content[$sec][$key]=$value;
        if($this->config['autoSave']===true)
        {
            $this->save();
        }
        return $this->content[$sec][$key];
    }

    /**
     *Function save
     *Save the alterations in the ini file
     *@Param string $filename [optional] Name of the file to save (if null, the alterations will go save in the current file)
     *@Return boolean true if saved, false if not saved
     *@Since Version 1.0
     *@Access public
    **/
    public function save($filename=null)
    {
        if($this->config['readOnly']===true)
        {
            trigger_error("[Class FileIni]: CNon posso modificare il file perchè read only", E_USER_ERROR);
            return false;
        }
        if(is_null($filename))
        {
            $filename=$this->filename;
        }
        $content="";
        foreach($this->content as $sec=>$itens)
        {
            $content.="[" . $sec . "]\n";
            foreach($itens as $key=>$value)
            {
                $content.=$key . "=" . $value . "\n";
            }
            $content.="\n";
        }
        if(!@file_put_contents($filename, $content))
        {
            trigger_error("[Class FileIni]: Non posso salvare il file", E_USER_WARNING);
            return false;
        }
        return true;
    }

    /**
     *Function setConfig
     *sete settings to manipulate the file
     *@Param array $config The configurations
     *@Since Version 1.0
     *@Access protected
    **/
    protected function setConfig($config)
    {
        if(!is_array($config))
        {
            trigger_error("[Class FileIni]: Le variabili devono essere un array", E_USER_WARNING);
            exit();
        }
        $this->config['autoSave']=(isset($config['autoSave']))?(boolean)$config['autoSave']:true;
        $this->config['readOnly']=(isset($config['readOnly']))?(boolean)$config['readOnly']:false;
    }
}
?>
