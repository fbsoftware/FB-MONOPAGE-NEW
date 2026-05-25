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

    fields: {
      width: {
        type: "range",
        min: 10,
        max: 100,
        label: "Larghezza",
        group:"contenuto"
      },
        height: {
        type: "number",
        label: "Altezza",
        group:"contenuto"
      },  
        padding: {
        type: "number",
        label: "Padding",
        group: "stile"
      },
        margin: {
        type: "number",
        label: "Margin",
        group: "stile"
      },
        background: {
        type: "color",
        label: "Sfondo",
        group: "stile"
      },
        customCss: {
        type:"textarea",
        label:"CSS personalizzato",
        group:"avanzate"
    }
    },

    render(column){
      const p = column;

      return `
      <div class="canvas-column"
           data-id="${column.id}"
           style="width:${p.width ||100}%;
            height:${p.height ||200}px;
            padding:${p.padding ||20}px;
            margin:${p.margin || 0}px;
            background:${p.background ||'transparent'};
            ${p.customCss}">
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
    editor.renderColumnInspector(column);
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
        .css("flex-basis", (column.width ?? 100) + "%")
        .css("height", (column.height ?? 200) + "px")
        .css("padding", (column.padding ?? 20) + "px")
        .css("margin", (column.margin ?? 0) + "px")
        .css("background", column.background || "transparent")
        .css("flex-grow", 0)
        .css("flex-shrink", 0);
        // customCss
        if(column.customCss){
        $column.attr(
            "style",
            ($column.attr("style") || "") + ";" + column.customCss
        );
    }

    const $toolbar = $("<div>")
        .addClass("column-toolbar")
        .html(`
        <button class="duplicate-column button">
            <span class="material-symbols-outlined">content_copy</span>
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

//=================================
// clonare colonna e widget
//=================================
editor.cloneColumn = function(column){

    const clone = structuredClone
        ? structuredClone(column)
        : JSON.parse(JSON.stringify(column));

    clone.id = editor.uid("col");

    clone.widgets = (clone.widgets || []).map(widget => {
        widget.id = editor.uid("w");
        return widget;
    });

    return clone;
};

//=======================================
//  clic duplicazione colonna
//=======================================

$(document).on("click", ".duplicate-column, .duplicate-column *", function(e){
    e.preventDefault();
    e.stopPropagation();

    const $btn = $(this).closest(".duplicate-column");
    const columnId = $btn.closest(".canvas-column").data("id");

    console.log("CLICK DUPLICA COLONNA", columnId);

    if(!columnId) return;

    let foundSection = null;
    let foundIndex = -1;

    (editor.state.sections || []).forEach(section => {
        const index = (section.columns || []).findIndex(col => col.id === columnId);

        if(index !== -1){
            foundSection = section;
            foundIndex = index;
        }
    });

    if(!foundSection || foundIndex === -1) return;

    const originalColumn = foundSection.columns[foundIndex];

    const newColumn = JSON.parse(JSON.stringify(originalColumn));
    newColumn.id = "col-" + Date.now() + "-" + Math.floor(Math.random() * 1000);

    newColumn.widgets = (newColumn.widgets || []).map(widget => {
        widget.id = "w" + Date.now() + "_" + Math.floor(Math.random() * 1000);
        return widget;
    });

    foundSection.columns.splice(foundIndex + 1, 0, newColumn);

    editor.state.selectedType = "column";
    editor.state.selectedId = newColumn.id;
    editor.state.isDirty = true;

    editor.render();
    editor.openColumnInspector(newColumn.id);
});

//======================
// dettagli per colonna
//======================
editor.renderColumnInspector = function(column){

    editor.renderElementInspector(
        "<h3 style='text-align:center; margin:10px;'>Dettagli Colonna</h3>",
        column,
        editor.columns.column.fields,
        "column"
    );
};

//========================
//  handle colonne
//========================
$(document).on("input change", "#inspector [data-column-field]", function(e){

    const tag = this.tagName.toLowerCase();
    const field = $(this).data("column-field");

    // Durante la scrittura nella textarea salvo solo il valore,
    // ma non rifaccio il render.
    if(tag === "textarea" && e.type === "input"){
        const column = editor.findColumnById(editor.state.selectedId);
        if(column){
            column[field] = $(this).val();
            editor.state.isDirty = true;
        }
        return;
    }

    let value = $(this).val();
    const type = $(this).attr("type");

    if(type === "number" || type === "range"){
        value = parseInt(value, 10);
        if(isNaN(value)) value = 0;
    }

    const column = editor.findColumnById(editor.state.selectedId);
    if(!column) return;

    column[field] = value;

    editor.state.isDirty = true;

    editor.render();
    editor.openColumnInspector(column.id);
});