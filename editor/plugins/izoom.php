<?php
/**
  ----------------------------------------
	04/05/24    modulo immagine zoomabile
------------------------------------------- */
echo	"<section id='izoom' class='editor-widget' data-widget='izoom'>";
// cerca immagine zoomabile da pubblicare
       $sql = "SELECT *
                FROM `".DB::$pref."zim`
                WHERE ztmp = '".TMP::$tcod."'
                              and zstat <> 'A'
				        AND zcod = '$lcod'  
                ORDER BY zprog ";
     foreach($PDO->query($sql) as $row)

     {
        require 'admin/fields_zim.php'; 
$img = $zimg;
     } 
?>

<style>
.container {
    display: flex;
    gap: 25px;
    align-items: flex-start;
    background: var(--sec-color);
}

/* Immagine principale */
#mainImg {
    width: 350px;
    height: auto;
    cursor: crosshair;
    display: block;
    border: 1px solid #ccc;
}

/* Div dello zoom */
#zoomBox {
    width: 350px;
    height: 350px;
    border: 2px solid #555;
    background-repeat: no-repeat;
    display: none;
    overflow: hidden;
}
</style>
</head>
<body>

<div class="container">

    <!-- Immagine caricata da MySQL -->
    <img id="mainImg" src="<?= $img ?>">

    <!-- Div affiancato che mostra lo zoom -->
    <div id="zoomBox"></div>

</div>

<script>
const img = document.getElementById("mainImg");
const zoom = document.getElementById("zoomBox");

let zoomFactor = 3; // Livello di zoom

// Imposta l'immagine come sfondo del box zoom
zoom.style.backgroundImage = `url('${img.src}')`;

img.addEventListener("mousemove", function(e) {

    zoom.style.display = "block";

    // coordinate del mouse rispetto all'immagine
    const rect = img.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;

    // Calcola la dimensione dello zoom
    zoom.style.backgroundSize =
        `${img.width * zoomFactor}px ${img.height * zoomFactor}px`;

    // Calcola la posizione all'interno dello zoom
    const posX = (x / img.width) * 100;
    const posY = (y / img.height) * 100;

    zoom.style.backgroundPosition = `${posX}% ${posY}%`;
});

img.addEventListener("mouseleave", function() {
    zoom.style.display = "none";
});
</script>

</body>
</html>
