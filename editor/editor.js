var editor = editor || {};

editor.init = function () {

    try {
        editor.renderWidgetPalette();
    } catch (err) {
        console.error("ERRORE renderWidgetPalette:", err);
        return;
    }

//=================================
//editor.renderWidgetPalette();   // ← QUESTO CARICA I WIDGET NELLA PALETTE
//console.log('palette-dopo');
//=================================
// Carica layout iniziale
         if (window.INITIAL_LAYOUT && window.INITIAL_LAYOUT.sections) {
        editor.state = window.INITIAL_LAYOUT;

    } else {
// Se non c'è un layout iniziale, creane uno di default
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

$(document).ready(function () {
    editor.init();
});
