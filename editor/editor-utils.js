var editor = editor || {};
editor.utils = editor.utils || {};

//==============================
// Generatore ID unico
//==============================
editor.utils.uuid = function(prefix = "id") {

    return prefix + "-" +
           Date.now() + "-" +
           Math.floor(Math.random() * 1000);

};

//==========================================
//  CERCA SEZIONE
//==========================================

editor.findSectionById = function(sectionId){

    let found = null;

    editor.state.sections.forEach(section => {
        if(section.id === sectionId){
            found = section;
        }
    });

    return found;
};