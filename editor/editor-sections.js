
//=================================
// ➕ SEZIONE
//=================================
$(document).on("click", "#add-section", function(){
    editor.createSection();
});

//=================================
//  ➕ Sezione HERO
//=================================
$(document).on("click", ".add-hero-section", function(){
    editor.state.sections.push(editor.createHeroSection());
    editor.render();
});

//=================================
//  FIELDS SEZIONE
//================================= 
editor.sectionFields = {
    backgroundType: {
        type: "select",
        label: "Tipo sfondo",
        options: {
            color: "Colore",
            image: "Immagine"
        }
    },

    backgroundImage: {
        type: "text",
        label: "URL immagine"
    },

    minHeight: {
        type: "number",
        label: "Altezza minima"
    },

    backgroundSize: {
        type: "select",
        label: "Adatta immagine",
        options: {
            cover: "Cover",
            contain: "Contain",
            auto: "Auto"
        }
    },

    backgroundPosition: {
        type: "select",
        label: "Posizione immagine",
        options: {
            "left top": "Sinistra alto",
            "center top": "Centro alto",
            "center center": "Centro",
            "center bottom": "Centro basso"
        }
    },

    overlayColor: {
        type: "text",
        label: "Overlay"
    },

    verticalAlign: {
        type: "select",
        label: "Allineamento contenuti",
        options: {
            top: "Alto",
            center: "Centro",
            bottom: "Basso"
        }
    }
};
//=================================
// Move section UP DOWN           |
//=================================
editor.moveSection = function(sectionId, direction) {

    const sections = editor.state.sections;

    const index = sections.findIndex(sec => sec.id === sectionId);

    if (index === -1) return;

    if (direction === "up" && index > 0) {

        [sections[index - 1], sections[index]] =
        [sections[index], sections[index - 1]];

    }

    if (direction === "down" && index < sections.length - 1) {

        [sections[index + 1], sections[index]] =
        [sections[index], sections[index + 1]];

    }

    editor.render();
};
//=================================
// Delete section
//=================================
editor.deleteSection = function(sectionId){

    if(!confirm("Eliminare questa sezione?")) return;

    editor.state.sections = editor.state.sections.filter(
        s => s.id !== sectionId
    );

    editor.render();
    
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

            <label for="sec-minHeight">Altezza Minima</label>
                <input
                id="sec-minHeight"
                type="number"
                value="${section.minHeight || "0"}"
                data-section-field="minHeight"
            >
                <label for="sec-verticalAlign">Allineamento verticale</label>
                <select id="sec-verticalAlign" data-section-field="verticalAlign">
                    <option value="top" ${section.verticalAlign === "top" ? "selected" : ""}>Alto</option>
                    <option value="center" ${section.verticalAlign === "center" ? "selected" : ""}>Centro</option>
                    <option value="bottom" ${section.verticalAlign === "bottom" ? "selected" : ""}>Basso</option>
                </select>       
                
        </div>
    `;

    $panel.html(html);
};
//=================================
// Render section
//=================================
editor.renderSection = function(section){

    const selected =
        editor.state.selectedType === "section" &&
        section.id === editor.state.selectedId
        ? "selected"
        : "";

    const $section = $("<div>")
        .addClass(`canvas-section ${selected}`)
        .attr("data-id", section.id)
        .css("background-color", section.background || "transparent")
        .css("padding", (section.padding ?? 20) + "px")
        .css("margin", (section.margin ?? 0) + "px")
        .css("min-height", (section.minHeight ?? 0) + "px")
        .css("align-items", editor.getSectionVerticalAlign(section.verticalAlign));

    const $toolbar = $("<div>")
        .addClass("section-toolbar")
        .html(`
            <button class="move-up button">
                <span class="material-symbols-outlined">arrow_upward</span>
            </button>

            <button class="move-down button">
                <span class="material-symbols-outlined">arrow_downward</span>
            </button>

            <button class="duplicate button">
                <span class="material-symbols-outlined">content_copy</span>
            </button>

            <button class="delete-section button">
                <span class="material-symbols-outlined">delete</span>
            </button>

            <button class="add-column button">
                <span class="material-symbols-outlined">add_column_right</span>
            </button>
        `);

    $section.prepend($toolbar);

    const $columns = $("<div>").addClass("section-columns");

    if(section.columns && section.columns.length){
        section.columns.forEach(col => {
            $columns.append(editor.renderColumn(col));
        });
    } else {
        $columns
            .addClass("empty-columns")
            .append(`<div class="empty-dropzone">Trascina qui una colonna</div>`);
    }

    $section.append($columns);

    return $section;
};
//=================================
//  Create new section
//=================================
 editor.createSection = function(){

    const section = {
        id: editor.utils.uuid("sec"),
        background: "#ffffff    ",
        padding: "20",
        margin: "0",
        columns: [
            {
                id: editor.utils.uuid("col"),
                width: 50,
                widgets: []
            }
        ]
    };

    editor.state.sections.push(section);

    editor.state.selectedType = "section";
    editor.state.selectedId = section.id;

    editor.render();
};

//=================================
// Normalize section column widths
//=================================
editor.normalizeSectionWidths = function(section){

    if(!section || !section.columns || !section.columns.length) return;

    const count = section.columns.length;
    const width = Math.floor(100 / count);
    let total = 0;

    section.columns.forEach((col, index) => {
        if(index < count - 1){
            col.width = width;
            total += width;
        } else {
            col.width = 100 - total;
        }
    });
};


//=================================
//  Create Hero section
//=================================
editor.createHeroSection = function(){
console.log("Creazione sezione Hero");
    return {
        id: editor.utils.uuid("sec"),
        background: "transparent",
        padding: 0,
        margin: 0,

        backgroundType: "image",
        backgroundImage: "https://picsum.photos/1600/900",
        backgroundSize: "cover",
        backgroundPosition: "center center",
        backgroundRepeat: "no-repeat",
        minHeight: 500,
        overlayColor: "rgba(0,0,0,0.35)",
        verticalAlign: "center",

        columns: [
            {
                id: editor.utils.uuid("col"),
                width: 100,
                widgets: [
                    {
                        id: editor.uid(),
                        type: "header",
                        props: {
                            text: "Titolo Hero",
                            level: "h1",
                            align: "center",
                            color: "#000000"
                        }
                    },
                    {
                        id: editor.uid(),
                        type: "text",
                        props: {
                            text: "Qui puoi inserire un testo introduttivo.",
                            align: "center",
                            color: "#000000"
                        }
                    },
                    {
                        id: editor.uid(),
                        type: "button",
                        props: {
                            text: "Scopri di più",
                            url: "#",
                            align: "center"
                        }
                    }
                ]
            }
        ]
    };
};

//================================= 
//  supporto allineamento verticale contenuti
//=================================
editor.getSectionVerticalAlign = function(value){
    switch(value){
        case "top": return "flex-start";
        case "bottom": return "flex-end";
        case "center":
        default: return "center";
    }
};