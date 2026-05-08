var editor = editor || {};

editor.init = function () {
    // carica menu iniziale e renderizza il builder del menu
    editor.navState = {
    isDirty: false
    };
    editor.loadAvailablePages();    
    editor.loadSiteMenu(); 
    editor.renderSiteMenuBuilder();    
    };

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$(document).ready(function(){
   editor.init();
});