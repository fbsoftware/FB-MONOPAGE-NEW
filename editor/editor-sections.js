//===============================================================
// Sezioni - proprietà + campi di modifica
//==============================================================
editor.sections = {

    section: {
        label: "Sezione",
        icon: "🧱",

        defaultProps: {
            background: "transparent",
            padding: 20,
            margin: 0,
            customCss: ""
        },

        fields: {
            background: {
                type: "color",
                label: "Sfondo",
                group: "stile"
            },

            padding: {
                type: "number",
                label: "Padding px",
                group: "stile"
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
            }
        }
    }
};

//=================================
// RENDER INSPECTOR SEZIONI
//=================================
editor.renderSectionInspector = function(section){

    if(!section) return;

    editor.renderElementInspector(
        "Sezione",
        section,
        editor.sections.section.fields,
        "section"
    );
};

//========================
// handle sezioni
//========================
$(document).on("input change", "#inspector [data-section-field]:not(textarea)", function(e){

    const field = $(this).data("section-field");
    const type = $(this).attr("type");

    let value = $(this).val();

    if(type === "number" || type === "range"){
        value = parseInt(value, 10);
        if(isNaN(value)) value = 0;
    }

    const section = editor.findSectionById(editor.state.selectedId);
    if(!section) return;

    section[field] = value;

    editor.state.isDirty = true;

    editor.render();
    editor.openSectionInspector(section.id);
});

//=================================
// Handle textarea separately to avoid input lag
//=================================
$(document).on("blur", "#inspector textarea[data-section-field]", function(){

    const field = $(this).data("section-field");
    const value = $(this).val();

    const section = editor.findSectionById(editor.state.selectedId);
    if(!section) return;

    section[field] = value;

    editor.state.isDirty = true;

    editor.render();
    editor.openSectionInspector(section.id);
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

    const $section = $("<div>")
        .addClass(`canvas-section ${selected}`)
        .attr("data-id", section.id)
        .css("background-color", section.background || "transparent")
        .css("padding", (section.padding ?? 20) + "px")
        .css("margin", (section.margin ?? 20) + "px");
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

    const $columns = $("<div>").addClass("section-columns");

        section.columns.forEach(col => {
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
        background: "#ffffff    ",
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

    const res = await fetch("/FB-JSON/data/templates/sections/" + file);
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