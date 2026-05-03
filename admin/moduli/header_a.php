<?php
/**
18/04/22  flex
*/
echo "<header>";
echo "<div class='f-flex fd-row jc-between ai-center'
        style='background:#dadada;'>";

    echo "<div>";
        echo "<img class='marchio' src='images/logo/logo.png' alt='logo.png' title='logo' >";
    echo "</div>";

    echo "<div>";
        echo "<h3>Amministratore</h3>";
    echo "</div>";

    echo "<div class='f-flex fd-row jc-between ai-center'>";
        require_once('moduli/nav.php');
    echo "</div>";

    echo "<div>";
        echo "<p class='little'>
            Versione ".DB::$livello.".".DB::$rilascio.".".DB::$modify.":&nbsp;
            <br />";
    //  bottone logout
    echo "<div>";
/*        echo "<form class='bottoni' method='post' action='login.php'>";
            echo "<button class='fb-p025 fb-rad7 fb-m05' type='submit' name='submit' value='chiudi'
                            style='background-color: #f44336; color: white; border: none; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; cursor: pointer;'> ";
            echo "<img src='".DB::$dir_imm."uscita.png' alt='uscita' height='25'>";
            echo "&nbsp;&nbsp;&nbsp;&nbsp;Uscita";
            echo "</button>";*/
        echo "</form>";
    echo "</p></div>";


echo "</div>";// flex

echo "</header>";
?>
