
//=================================
// ➕ SEZIONE
//=================================
$(document).on("click", "#add-section", function(){
    editor.createSection();
});
//=================================
// Move section
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
        .css("background", section.background || "transparent")
        .css("padding", section.padding + "px"|| "20px")
        .css("margin", section.margin + "px" || "0px");

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

    if(section.columns){
        section.columns.forEach(col => {
            $columns.append(editor.renderColumn(col));
        });
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
        background: "var(--color-bg)",
        padding: "20",
        margin: "0",
        columns: [
            {
                id: editor.utils.uuid("col"),
                width: 100,
                widgets: []
            }
        ]
    };

    editor.state.sections.push(section);

    editor.state.selectedType = "section";
    editor.state.selectedId = section.id;

    editor.render();
};
