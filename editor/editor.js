var editor = editor || {};

editor.init = function () {

    console.log("INIT PARTITO");

    try {
        console.log("PRIMA PALETTE");
        editor.renderWidgetPalette();
        console.log("DOPO PALETTE");
    } catch (err) {
        console.error("ERRORE renderWidgetPalette:", err);
        return;
    }

    // Carica layout iniziale
    if (window.INITIAL_LAYOUT && window.INITIAL_LAYOUT.sections) {
        editor.state = window.INITIAL_LAYOUT;
    } else {
        editor.createSection();
    }

    // Carica config globale
    if(window.SITE_CONFIG){
        editor.applySiteConfigToForm(window.SITE_CONFIG);
        editor.applyGlobalCssVariables(window.SITE_CONFIG);

        editor.state.global.colors = window.SITE_CONFIG.colors || {};
        editor.state.global.typography = window.SITE_CONFIG.typography || {};
    }
// Carica cartelle gallery
if (typeof editor.loadGalleryFolders === "function") {
    editor.loadGalleryFolders();
} else {
    console.warn("loadGalleryFolders non disponibile");
}

    editor.bindSiteConfigSave();

    editor.state.meta.updated_at = new Date().toISOString();

    editor.render();
};

$(document).ready(function () {
    editor.init();
});
