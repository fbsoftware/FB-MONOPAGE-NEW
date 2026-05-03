<?php
spl_autoload_register('my_autoloader');  

function my_autoloader($className) {
    $path = '../libFB_2_0_0/';
    $file = $path . $className . '.php';
    // LOG
    error_log("AUTOLOAD: " . $className . " => " . $file);
    if (file_exists($file)) {
        require $file;
    } else {
        error_log("FILE NON TROVATO: " . $file);
    }
}
?>