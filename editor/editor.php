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
<link rel="stylesheet" href="editor.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
    <body>

<?php
$page = $_GET['page'] ?? 'home';
$page = preg_replace('/[^a-zA-Z0-9\-_]/', '', $page);

$layoutFile = dirname(__DIR__) . "/data/{$page}.json";
$layoutData = null;

if (file_exists($layoutFile)) {
    $layoutData = json_decode(file_get_contents($layoutFile), true);
}
?>

<div id="editor">

<div id="tabs">
  <ul>
    <li><a href="#widgets-panel">Elementi</a></li>
    <li><a href="#widget-inspector">Dettagli</a></li>
    <li><a href="#global">Global</a></li>
    </ul>


            <!-- widgets ----------------------------------------------- -->
    <div id="accordion">           
            <h3 class="" aria-expanded="true" aria-selected="true">Widget</h3>
            <div id="widgets-panel">  </div>

            <!-- plugins ----------------------------------------------- 
            <h3 class="">Plugin</h3>        
            <div>
                <ul class="widget-list">
                    <?php foreach (glob('plugins/*.php') as $file): ?>
                    <?php $name = basename($file, '.php'); ?>
                    
                    <div class="palette-widget" data-widget="<?= $name ?>" data-type="<?= $name ?>" data-kind="plugin">
                        <?= ucfirst($name) ?>
                    </div>
                    <?php endforeach; ?>
                </ul>
            </div>  -->
    </div> <!-- accordion -->
        <!-- impostazioni globali---------------------------- -->
      
    <div id="global">
     <h3 class="" aria-expanded="true" aria-selected="true">Global</h3>     
                <!-- 🎨 COLORI -->
                <section class="style-section">
                    <label> 🎨 COLORI</label>
                    <br><br>

                    <div class="style-control" style="display: flex;">
                        <label>Primary</label>
                        <input type="color" value="#3366ff" data-global-color="primary">
                        </div>
                    <div class="style-control" style="display: flex;">
                        <label>Secondary</label>
                        <input type="color" value="#ff6633" data-global-color="secondary">              
                        </div>
                    <div class="style-control" style="display: flex;">
                        <label>Accent</label>
                        <input type="color" value="#ffa500" data-global-color="accent">
                    </div>
                    <div class="style-control" style="display: flex;">
                        <label>Testo</label>
                        <input type="color" value="#222222" data-global-color="text">
                    </div>
                    <div class="style-control" style="display: flex;">
                        <label>Sfondo</label>
                        <input type="color" value="#ffffff" data-global-color="bg">
                </section>

                <!-- 🔤 TIPOGRAFIA -->
                <section class="style-section">
                     <label>🔤 TIPOGRAFIA</label>
                    <br><br>
                    <div class="style-group" style="display: flex;">
                    <label>Font titoli</label>
                    <select type="typography" data-global-font="heading-family">
                        <option value="Inter">Inter</option>
                        <option value="Montserrat">Montserrat</option>
                        <option value="Poppins">Poppins</option>
                        <option value="Roboto">Roboto</option>
                    </select>
                    </div>
                    <div class="style-group" style="display: flex;">
                    <label>Stile titoli</label>
                    <select type="typography" data-global-font="heading-weight">
                        <option value="400">Regular</option>
                        <option value="600">Semi-bold</option>
                        <option value="700">Bold</option>
                    </select>
                    </div>

                    <div class="style-group" style="display: flex;">
                    <label>Font body</label>
                    <select type="typography" data-global-font="body-family">
                        <option value="Inter">Inter</option>
                        <option value="Montserrat">Montserrat</option>
                        <option value="Poppins">Poppins</option>
                        <option value="Roboto">Roboto</option>
                    </select>
                    </div>

                    <div class="style-group" style="display: flex;">
                    <label>Stile body</label>
                    <select type="typography" data-global-font="body-weight">
                        <option value="400">Regular</option>
                        <option value="500">Medium</option>
                    </select>
                    </div>
                </section>
        <!--SALVATAGGIO CONFIGURAZIONE  -->
                <button id="saveSiteConfig" class="button2">💾 Salva Impostazioni</button>
                <span id="siteConfigStatus"></span>
            
    </div> <!-- global -->




  <!-- DETTAGLI -->
  
    <div id="inspector">
        <h3 class="" aria-expanded="true" aria-selected="true">Dettagli</h3> 
    </div><!-- elementi/dettagli -->
     
</div> <!-- tabs -->


    <!-- CANVAS -->
    <div class="canvas-panel">
                <div style="flex:2">&nbsp;</div>
                <div style="display: flex; gap: 250px; align-items: center; margin-bottom: 20px;">
                    <div style="flex:3">     
                        <h2 style="text-align:center">Layout del tema <span style="color: black"><?=$tema?></span> pagina <span style="color: black"><?=$page?></span></h2>  
                    </div>
                    <div style="flex:1">
                        <button id="save-layout">Pubblica</button>
                    </div>                    
                </div> 


        <div>
            <div id="canvas" class="canvas"> </div>
            <div style="display:flex; justify-content:center">
            <button id="add-section" class="add-section-btn">➕ Sezione</button>
            </div>
        </div>
       
    </div>  
   
</div> <!--#editor-->


<script>
window.EDITOR_CONFIG = {
    tema: null,
    page: "<?= $_GET['page'] ?? 'home' ?>"
};

  // Carica configurazione globale
    <?php
        $siteConfigFile = "site-config.json";
        $siteConfig = [];
        if(file_exists($siteConfigFile)){
            $siteConfig = json_decode(file_get_contents($siteConfigFile), true);
        }
    ?>
    window.SITE_CONFIG = <?= json_encode($siteConfig, JSON_PRETTY_PRINT) ?>;

// Carica layout iniziale
window.INITIAL_LAYOUT = <?= json_encode($layoutData) ?>;
</script>


<script>
window.EDITOR_CONFIG = {
    page: "<?= $page ?>"
};

window.INITIAL_LAYOUT = <?= json_encode($layoutData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?>;

console.log("EDITOR PAGE =", window.EDITOR_CONFIG.page);
console.log("INITIAL_LAYOUT =", window.INITIAL_LAYOUT);
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
     $( function() {
    $( "#tabs" ).tabs();
  } );        
  } );
 </script>


<script src="editor-utils.js"></script>
<script src="editor-sections.js"></script>
<script src="editor-columns.js"></script>
<script src="editor-widgets.js"></script>
<script src="editor-dragdrop.js"></script>
<script src="editor-inspector.js"></script>
<script src="editor-state.js"></script>
<script src="editor-render.js"></script>        <!-- quì editor.render() definizione -->
<script src="HTML.js"></script>
<script src="editor-core.js"></script>
<script src="editor.js"></script>             <!-- quì editor.init() defin. + uso -->

</body>
</html>