var editor = editor || {};

editor.init = function () {
    // carica menu iniziale
editor.renderWidgetPalette();   // ← QUESTO CARICA I WIDGET NELLA PALETTE
// Carica layout iniziale
//console.log("INITIAL LAYOUT=",window.INITIAL_LAYOUT);
         if (window.INITIAL_LAYOUT && window.INITIAL_LAYOUT.sections) {
        editor.state = window.INITIAL_LAYOUT;

    } else {
// Se non c'è un layout iniziale, creane uno di default
//console.log("Nuovo layout");
        editor.createSection();
    }
 // Popolo i dati globali da site-config.json
    if(window.SITE_CONFIG){
        editor.state.global.colors = window.SITE_CONFIG.colors || {};
        editor.state.global.typography = window.SITE_CONFIG.typography || {};
        editor.state.global.fonts  = window.SITE_CONFIG.fonts || {};
        }

    // Aggiorno updated_at
    editor.state.meta.updated_at = new Date().toISOString();

    editor.render();
};

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
$(document).ready(function(){

    editor.siteMenu = [
        { title: "Home", page: "home", level: 0 },
        { title: "Servizi", page: "servizi", level: 0 },
        { title: "Web Design", page: "web-design", level: 1 },
        { title: "Contatti", page: "contatti", level: 0 }
    ];

});
$(document).ready(function () {
    editor.init();

});
