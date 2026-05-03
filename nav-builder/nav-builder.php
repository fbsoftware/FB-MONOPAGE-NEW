        <!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>Navigatore - Step 1</title>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="nav-builder.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
    <body>

<div id="editor">

  <!-- PAGINE -->
  
    <div id="inspector">
        <h3 class="" aria-expanded="true" aria-selected="true">Pagine</h3> 
        <div id="available-pages-panel">
            <div style="flex:1">
                <button id="add-page-to-menu">➕ Pagina</button>                
            </div> 
        </div>
    </div><!-- pagine -->
     
    <!-- CANVAS -->

    <div class="canvas-panel">
        <div style="flex:1">&nbsp;</div>
            <div style="display: flex; gap: 25px; justify-content: space-between; margin-bottom: 20px;">
            
            <div style="flex:7">     
                <h2 style="text-align:center">Navigatore</h2>  
            </div>

            <div style="flex:1">
                <button id="save-site-menu">Salva menu</button>
            </div>  
            

        </div> 
            <div id="site-menu-builder"></div>
    </div>  
   
</div> <!--#editor-->

<script src="nav-builder.js"></script>
<script src="nav.js"></script>

</body>
</html>