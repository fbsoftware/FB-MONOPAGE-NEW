<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>Mini Elementor - Step 1</title>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="/FB-MONOPAGE/assets/css/editor.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
    <body>

<?php

require_once dirname(__DIR__) . '/config/app.php';

$page = $_GET['page'] ?? 'home';
$page = preg_replace('/[^a-zA-Z0-9\-_]/', '', $page);

$layoutFile = APP_ROOT . "/data/{$page}.json";



$layoutData = null;

if (file_exists($layoutFile)) {
    $layoutData = json_decode(file_get_contents($layoutFile), true);
}
?>

<div id="editor">

<div id="tabs">
  <ul>
    <li><a href="#widgets-panel">
                <span class="material-symbols-outlined" 
            style="font-size :30px !important;
                    color:var(--color-text)">                    
            widgets
            </span>
    
    </a></li>
    <li><a href="#widget-inspector">
                <span class="material-symbols-outlined" 
            style="font-size :30px !important;
                    color:var(--color-text)">                    
            edit
            </span>
    </a></li>
    <li><a href="#global">                
            <span class="material-symbols-outlined" 
            style="font-size :30px !important;
                    color:var(--color-text)">                    
            settings
            </span>
            </a></li>
    </ul>

    <div id="accordion">  
        <!-- widgets ----------------------------------------------- -->
        <div id="widgets-panel"></div>
        <!-- DETTAGLI ----------------------------------------------- -->
        <div id="widget-inspector"></div>        
        <!-- impostazioni globali---------------------------- -->
        <div id="global">
            <label for="color-primary">Colore primario:</label>
            <input id="color-primary" type="color">
            <label for="color-secondary">Colore secondario:</label>
            <input id="color-secondary" type="color">
            <label for="color-accent">Colore accento:</label>
            <input id="color-accent" type="color">
            <label for="color-text">Colore testo:</label>
            <input id="color-text" type="color">
            <label for="color-bg">Colore sfondo:</label>    
            <input id="color-bg" type="color">

            <label for="heading-family">Font heading:</label>
            <select id="heading-family">
                <option value="Inter">Inter</option>
                <option value="Montserrat">Montserrat</option>
                <option value="Poppins">Poppins</option>
                <option value="Roboto">Roboto</option>
            </select>

            <label for="heading-weight">Peso font heading:</label>
            <select id="heading-weight">
                <option value="400">Regular</option>
                <option value="600">Semi-bold</option>
                <option value="700">Bold</option>
            </select>

            <label for="body-family">Font body:</label>
            <select id="body-family">
                <option value="Inter">Inter</option>
                <option value="Montserrat">Montserrat</option>
                <option value="Poppins">Poppins</option>
                <option value="Roboto">Roboto</option>
            </select>

            <label for="body-weight">Peso font body:</label>
            <select id="body-weight">
                <option value="400">Regular</option>
                <option value="500">Medium</option>
                <option value="600">Semi-bold</option>
                <option value="700">Bold</option>
            </select>

            <label for="font-h1">Dimensione H1:</label>
            <input id="font-h1" type="number">
            <label for="font-h2">Dimensione H2:</label>
            <input id="font-h2" type="number">
            <label for="font-h3">Dimensione H3:</label>
            <input id="font-h3" type="number">
            <label for="font-body">Dimensione testo:</label>
            <input id="font-body" type="number">
            <label for="font-small">Testo piccolo:</label>
            <input id="font-small" type="number">
            <br /><br />
            <button id="saveSiteConfig">Salva config</button>
            <div id="siteConfigStatus"></div>
        </div>  <!-- global -->
    </div> <!-- accordion -->
</div> <!-- tabs -->


    <!-- CANVAS 
    <?php include '../includes/nav.php'; ?> -->

    <div class="canvas-panel">
                <div style="flex:2">&nbsp;</div>
                <div style="display: flex; gap: 250px; justify-content: space-between; margin-bottom: 20px;">
                    <div style="flex:7">     
                        <h2 style="text-align:center">Layout del tema: <span style="color: black"><?=$tema?></span> pagina: <span style="color: black"><?=$page?></span></h2>  
                    </div>
                    <div style="flex:1">
                        <button id="save-layout">Pubblica</button>
                    </div> 
                    <div style="flex:1">
                        <button id="editor-exit"> ⬅️ Esci</button>
                    </div> 
                </div> 


        <div>
            <div id="site-menu-builder"></div>
            <div id="canvas" class="canvas"> </div>
                <div style="display:flex; justify-content:center">
                    <button id="add-section" 
                            class="add-section-btn button"
                            style="border:1px solid black; margin:10px; border-radius:4px;    padding: 10px 15px;">➕ Sezione</button>
                    <button class="add-template button"
                            style="border:1px solid black; margin:10px; border-radius:4px;    padding: 10px 15px;">➕ Template</button>
                </div>
            </div>

            <!--  modal per templates -->
            <div id="template-modal" style="display:none;">
                <div class="overlay"></div>
                <div class="box">
                    <h3>Template</h3>
                    <div class="content"></div>
                    <button class="close-template">Chiudi</button>
                </div>
            </div>
        </div>  
    </div> 
</div> <!--#editor-->

<script>
window.FB_APP = {
    appUrl: "<?= APP_URL ?>",
    assetsUrl: "<?= ASSETS_URL ?>"
};
</script>
<script>
window.EDITOR_CONFIG = {
    tema: null,
    page: "<?= $_GET['page'] ?? 'home' ?>"
};

  // Carica configurazione globale
    <?php
$siteConfigFile = __DIR__ . "/site-config.json";
$siteConfig = [];
if(file_exists($siteConfigFile)){
    $siteConfig = json_decode(file_get_contents($siteConfigFile), true);
}
?>
window.SITE_CONFIG = <?= json_encode($siteConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?>;

// Carica layout iniziale
window.INITIAL_LAYOUT = <?= json_encode($layoutData) ?>;
</script>

 <script>
  $( function() {
    $( "#accordion" ).accordion({
        header: "h3",
        heightStyle: 'content',   
        collapsible: true,
        active: 0,  
        icons: { header: 'ui-icon-triangle-1-e', activeHeader: 'ui-icon-triangle-1-s' }
            });
            
    $( ".accordion" ).accordion({
        header: "h3",
        heightStyle: 'content',  
        collapsible: true,
        active: 0,  
        icons: { header: 'ui-icon-triangle-1-e', activeHeader: 'ui-icon-triangle-1-s' }
            });
    });
//=================================
// Tabs laterali editor
//=================================
$(function(){

    $("#tabs").tabs({
        active: 0,

        activate: function(event, ui){

            // chiude sempre tutti i pannelli
            $("#widgets-panel").hide();
            $("#widget-inspector").hide();
            $("#global").hide();

            // apre solo quello selezionato
            ui.newPanel.show();
        }
    });

    // stato iniziale
    $("#widgets-panel").hide();
    $("#widget-inspector").hide();
    $("#global").hide();
});
 </script>

<script src="editor-core.js"></script>
<script src="editor-utils.js"></script>
<script src="editor-sections.js"></script>
<script src="editor-columns.js"></script>
<script src="editor-widgets.js"></script>
<script src="editor-dragdrop.js"></script>
<script src="editor-state.js"></script>
<script src="editor-render.js"></script>        <!-- quì editor.render() definizione -->
<script src="editor.js"></script>             <!-- quì editor.init() defin. + uso -->
<script src="nav.js"></script>
</body>
</html>