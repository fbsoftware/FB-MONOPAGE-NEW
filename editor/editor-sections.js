//===============================================================
// Sezioni - proprietà + campi di modifica
//==============================================================
editor.sections = {

    section: {
        label: "Sezione",
        icon: "🧱",

        defaultProps: {
            anchor: "",
            background: "transparent",
            backgroundImage: "",
            backgroundPositionY: 50,
            overlayColor: "#000000",
            overlayOpacity: 0.4,
            height: 400,
            padding: 20,
            margin: 0,
            customCss: ""
        },

        fields: {
    background:{ type:"color", label:"Colore", group:"stile" },
    

            padding: {
                type: "number",
                label: "Padding px",
                group: "stile"
            },

            anchor: {
                type: "text",
                label: "Anchor (ID)",
                group: "avanzate"
            },

            margin: {
                type: "number",
                label: "Margin px",
                group: "stile"
            },

            customCss: {
                type: "textarea",
                label: "CSS personalizzato",
                group: "avanzate"
            },
            backgroundImage: {
                type: "image_picker",
                label: "Immagine sfondo",
                group: "immagini"
            },

            overlayColor: {
                type: "color",
                label: "Colore overlay",
                group: "overlay"
            },

            overlayOpacity: {
                type: "range",
                min: 0,
                max: 1,
                step: 0.1,
                label: "Opacità overlay",
                group: "overlay"
            },
                height: {
                type: "range",
                min: 100,
                max: 900,
                label: "Altezza sezione",
                group: "stile"
            },

            backgroundPositionY: {
                type: "range",
                min: 0,
                max: 100,
                label: "Posizione verticale immagine",
                group: "overlay"
            }
        }
    }
};

//=================================
// RENDER INSPECTOR SEZIONI
//=================================
editor.renderSectionInspector = function(section){

    if(!section) return;

    const bgImage = section.backgroundImage
    ? `url('${editor.resolveAssetUrl(section.backgroundImage)}')`
    : "none";

    editor.renderElementInspector(
        "<h3 style='text-align:center; margin:10px;'>Dettagli Sezione</h3>",
        section,
        editor.sections.section.fields,
        "section"
    );
};

//=================================
// Rimuovi immagine sfondo sezione
//=================================
$(document).on("click", ".clear-section-bg-image", function(e){

    e.preventDefault();
    e.stopPropagation();

    const section = editor.findSectionById(editor.state.selectedId);
    if(!section) return;

    section.backgroundImage = "";

    editor.state.isDirty = true;

    editor.render();
    editor.openSectionInspector(section.id);
});

//========================
// Handle sezioni
//========================
$(document).on("input change", "[data-section-field]", function(e){

    const field = $(this).data("section-field");
    const value = $(this).val();

    const section = editor.findSectionById(editor.state.selectedId);
    if (!section) return;

    section[field] = value;
    editor.state.isDirty = true;

    editor.render();
});

//=================================
// ➕ SEZIONE
//=================================
$(document).on("click", "#add-section", function(){
    editor.createSection();
});

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
// Render section
//=================================
editor.renderSection = function(section){

    const selected =
        editor.state.selectedType === "section" &&
        section.id === editor.state.selectedId
        ? "selected"
        : "";

    const bgImage = section.backgroundImage
        ? `url('${editor.resolveAssetUrl(section.backgroundImage)}')`
        : "none";
console.log("SECTION:", section.id, "ANCHOR:", section.anchor, section);
    const $section = $("<div id='" + section.anchor + "'>")
        .addClass(`canvas-section ${selected}`)
        .attr("data-id", section.id)
        .css("background-color", section.background || "transparent")
        .css("padding", (section.padding ?? 20) + "px")
        .css("margin", (section.margin ?? 20) + "px")
        .css("background-image", bgImage)
        .css("background-size", "cover")
        .css("background-position", "center")
        .css("position", "relative")
        .css("overflow", "hidden")
        .css("height", (section.height ?? 400) + "px")
        .css("background-position-y", (section.backgroundPositionY ?? 50) + "%");

    if(section.customCss){
        $section.attr(
            "style",
            ($section.attr("style") || "") + ";" + section.customCss
        );
    }

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

            <button class="save-section-template button">
                <span class="material-symbols-outlined">save</span>
            </button>
        `);

    $section.prepend($toolbar);

    if(section.backgroundImage){
        const $overlay = $("<div id='section.id'>")
            .addClass("section-overlay")
            .css("position", "absolute")
            .css("inset", 0)
            .css("background", section.overlayColor || "#000")
            .css("opacity", section.overlayOpacity ?? 0.4)
            .css("pointer-events", "none")
            .css("z-index", 1);

        $section.append($overlay);
    }

    const $columns = $("<div>")
        .addClass("section-columns")
        .css("position", "relative")
        .css("z-index", 2);

    (section.columns || []).forEach(col => {
        $columns.append(editor.renderColumn(col));
    });

    $section.append($columns);

    return $section;
};
//=================================
//  Create new section
//=================================
 editor.createSection = function(){

    const section = {
        id: editor.utils.uuid("sec"),
        background: "#ffffff",
        padding: "20",
        margin: "20",
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
// inserisce sezione nella pagina
//=================================
editor.insertSectionTemplate = async function(file){

    const res = await fetch("/FB-MONOPAGE/data/templates/sections/" + file);
    const data = await res.json();

    if(!data.section) return;

    const section = JSON.parse(JSON.stringify(data.section));

    section.id = editor.uid("sec");

    (section.columns || []).forEach(col => {
        col.id = editor.uid("col");

        (col.widgets || []).forEach(widget => {
            widget.id = editor.uid("w");
        });
    });

    editor.state.sections.push(section);
    editor.state.isDirty = true;

    editor.render();

    editor.state.selectedType = "section";
    editor.state.selectedId = section.id;
    editor.openSectionInspector(section.id);
};

//=================================
//  Editor Render - ricostruisce canvas
//================================= 
 editor.render = function () {
  $('#canvas').empty();

  editor.state.sections.forEach(section => {
    $('#canvas').append(editor.renderSection(section));
  });

  editor.initSortableWidgets();
  editor.initSortableColumns();
};

