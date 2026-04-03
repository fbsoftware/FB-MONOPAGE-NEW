<?php
/**
    Fausto Bresciani   fbsoftware@libero.it  www.faustobresciani.it
    package		Gestionale
    versione 1.0
    copyright	Copyright (C) 2019 - 2020 FB. All rights reserved.
    license		GNU/GPL
    Si concede licenza gratuita e NON si risponde di qualsiasi cosa dovuta
    all'uso anche improprio di FB open template.
================================================================================
  01/12/2025  Spaziatura
================================================================================*/
?>
<style>
.divider2 {
    display: flex;
    align-items: center;
    gap: 10px;
    background-color: var(--sec-color);
    height: 80px;
}

.divider2::before,
.divider2::after {
    content: "";
    flex: 1;
    border-bottom: 1px solid var(--pri-color);
}
.divider2 i {
    color: var(--pri-color);   /* Cambia colore qui */
    font-size: 24px; /* opzionale */
}

</style>
<?php
echo "<section id='space' class='editor-widget' data-widget='space'>";
// Spaziatura

 echo '
<div class="divider2">
    <i class="fa-solid fa-star"></i>
</div>';


echo "</section>";
?>
