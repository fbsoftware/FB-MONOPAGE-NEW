//=================================
//  Cancella colonna
//=================================
editor.deleteColumn = function(colId){

    editor.state.sections.forEach(section => {

        section.columns = section.columns.filter(
            col => col.id !== colId
        );

    });

    editor.render();

};

//=================================
//  + colonna
//=================================
editor.addColumn = function(sectionId){

    const section = editor.state.sections.find(s => s.id === sectionId);

    if(!section) return;

    section.columns.push({

        id: editor.generateId("col"),
        width: section.columns.length,
        widgets: []

    });

    editor.render();

};

//===============================================================
// Colonne - proprietà + campi di modifica
//==============================================================    
editor.columns = {

  column: {
    label:"Colonna",
    icon:"📝",

    defaultProps:{
      width: 100
    },

    fields: {
      width: {
        type: "range",
        min: 10,
        max: 100,
        label: "Larghezza"
      }
    },

    render(column){
      const p = column.props;
      return `
      <div class="canvas-column"
           data-id="${column.id}"
           style="width:${p.width ||100}%;">
      </div>
      `;
    }
  }
}

//=================================
// cerco la colonna selezionata
//=================================
editor.findColumnById = function(columnId){

    let found = null;

    editor.state.sections.forEach(section => {
        section.columns.forEach(column => {
            if(column.id === columnId){
                found = column;
            }
        });
    });

    return found;
};

//=================================
// colonna selezionata
//=================================
editor.selectColumn = function(id){
    editor.clearSelection();
    editor.state.selectedType = "column";
    editor.state.selectedId = id;
    editor.render();
    editor.openColumnInspector(id);
};

//=================================
// apre inspector colonna
//=================================
editor.openColumnInspector = function(Id){

    const column = editor.findColumnById(Id);

    if(!column){
        console.error("Colonna non trovata:", Id);
        return;
    }
console.log("COLONNA TROVATA");
    editor.renderColumnInspector(column);
};

//=================================
// Render column in inspector
//=================================
editor.renderColumnInspector = function(column){

    const $panel = $("#inspector");
    $panel.empty();

    const html = `
        <div class="inspector">
            <h3>Dettagli Colonna</h3>

            <label for="col-width-range">Larghezza (%)</label>

            <input
                id="col-width-range"
                type="range"
                min="10"
                max="100"
                step="1"
                value="${column.width}"
                data-column-field="width"
                data-column-input="range"
            >

            <input
                id="col-width-number"
                type="number"
                min="10"
                max="100"
                step="1"
                value="${column.width}"
                data-column-field="width"
                data-column-input="number"
            >
        </div>
    `;

    $panel.html(html);
};

//=================================
// Render column
//=================================
editor.renderColumn = function(column){

    const selected =
        editor.state.selectedType === "column" &&
        column.id === editor.state.selectedId
        ? "selected"
        : "";

    const $column = $("<div>")
        .addClass(`canvas-column ${selected}`)
        .attr("data-id", column.id)
        .css("flex-basis", column.width + "%")
        .css("flex-grow", 0)
        .css("flex-shrink", 0);

    const $toolbar = $("<div>")
        .addClass("column-toolbar")
        .html(`
            <button class="move-left button">
                <span class="material-symbols-outlined">arrow_back</span>
            </button>

            <button class="move-right button">
                <span class="material-symbols-outlined">arrow_forward</span>
            </button>

            <button class="delete-column button">
                <span class="material-symbols-outlined">delete</span>
            </button>
        `);

    $column.prepend($toolbar);

    (column.widgets || []).forEach(widget => {
        $column.append(editor.renderWidget(widget));
    });

    return $column;
};
//=================================
//  State colonne
//=================================
editor.syncColumnsState = function(){

    editor.state.sections.forEach(section => {
        const $row = $(".canvas-section[data-id='"+section.id+"'] .canvas-column");
        const newOrder = [];

        $row.children(".canvas-column").each(function(){
            const id = $(this).data("id");
            const column = section.columns.find(c => c.id === id);
            if(column) newOrder.push(column);
        });

        section.columns = newOrder;
    });
};

//=================================
// Sortable columns between sections
//=================================
editor.initSortableColumns = function(){

    if ($(".section-columns").data("ui-sortable")) {
        $(".section-columns").sortable("destroy");
    }

    $(".section-columns").sortable({
        items: "> .canvas-column",
        connectWith: ".section-columns",
        placeholder: "column-placeholder",
        tolerance: "pointer",
        forcePlaceholderSize: true,

        start: function(event, ui){
            ui.placeholder.height(ui.item.outerHeight());
            ui.placeholder.width(ui.item.outerWidth());

            $(".empty-dropzone").hide();
        },

        stop: function(event, ui){
            editor.syncAllColumnsFromDOM();
            editor.render();
        }
    });
};
//=================================
// Sync all columns from DOM to state
//=================================
editor.syncAllColumnsFromDOM = function(){

    const colMap = {};

    editor.state.sections.forEach(section => {
        (section.columns || []).forEach(col => {
            colMap[col.id] = col;
        });
    });

    editor.state.sections.forEach(section => {
        section.columns = [];
    });

    $(".canvas-section").each(function(){
        const sectionId = $(this).attr("data-id");
        const section = editor.state.sections.find(s => s.id === sectionId);

        if(!section) return;

        $(this)
            .children(".section-columns")
            .children(".canvas-column")
            .each(function(){
                const colId = $(this).attr("data-id");
                if(colMap[colId]){
                    section.columns.push(colMap[colId]);
                }
            });
    });

    editor.state.sections.forEach(section => {
        editor.normalizeSectionWidths(section);
    });
};