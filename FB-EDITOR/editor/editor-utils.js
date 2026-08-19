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


//=================================
//  CAMPI NUMERICI
//=================================
editor.inspectorNumberField = function(elementType, prop, id, label, value = 0, options = {}) {

    const min = options.min ?? 0;
    const max = options.max ?? 100;
    const step = options.step ?? 1;

    const dataAttrs = {
        widget: "data-widget-field",
        column: "data-column-field",
        section: "data-section-field"
    };

    const dataAttr = dataAttrs[elementType];
    if (!dataAttr) return "";

    return `
        <div class="inspector-number-field">
            <label for="${id}">${label}</label>
            <input
                id="${id}"
                type="number"
                min="${min}"
                max="${max}"
                step="${step}"
                value="${value ?? 0}"
                ${dataAttr}="${prop}"
            >
        </div>
    `;
};

