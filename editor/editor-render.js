//=================================
//  Editor Render - ricostruisce canvas
//================================= 
editor.render = function() {
    const $canvas = $("#canvas");
    $canvas.empty();

    editor.state.sections.forEach(function(section) {
        const $section = editor.renderSection(section);
         $canvas.append($section);
    });

    editor.initSortableWidgets();
};



//=================================
//  APRE INSPECTOR SEZIONI
//=================================
editor.openSectionInspector = function(sectionId){

    const section = editor.findSectionById(sectionId);

    if(!section){
        console.error("Sezione non trovata:", sectionId);
        return;
    }

    editor.renderSectionInspector(section);
};

//=================================
//  RENDER INSPECTOR SEZIONI
//=================================
editor.renderSectionInspector = function(section){

    const $panel = $("#inspector");
    $panel.empty();

    const html = `
        <div class="inspector">
            <h3>Sezione</h3>

            <label for="sec-background">Sfondo</label>
            <select id="sec-background" data-section-field="background">
                <option value="var(--color-primary)" ${section.background === "var(--color-primary)" ? "selected" : ""}>Primario</option>
                <option value="var(--color-secondary)" ${section.background === "var(--color-secondary)" ? "selected" : ""}>Secondario</option>
                <option value="var(--color-accent)" ${section.background === "var(--color-accent)" ? "selected" : ""}>Accent</option>
                <option value="var(--color-text)" ${section.background === "var(--color-text)" ? "selected" : ""}>Testo</option>
                <option value="var(--color-bg)" ${section.background === "var(--color-bg)" ? "selected" : ""}>Sfondo</option>
                <option value="var(--color-custom)" ${section.background === "var(--color-custom)" ? "selected" : ""}>Custom</option>
            </select>
               <input
                id="sec-background"
                type="color"
                value="${section.background}"
                data-section-field="background"
            >

            <label for="sec-padding">Padding</label>
            <input
                id="sec-padding"
                type="number"
                value="${section.padding || "20"}"
                data-section-field="padding"
            >

            <label for="sec-margin">Margin</label>
            <input
                id="sec-margin"
                type="number"
                value="${section.margin || "0"}"
                data-section-field="margin"
            >
        </div>
    `;

    $panel.html(html);
};