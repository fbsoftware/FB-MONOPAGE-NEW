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
      width: 100,
      padding: 20,  
      margin: 20,
      background: "transparent"  
      
    },

    fields: {
      width: {
        type: "range",
        min: 10,
        max: 100,
        label: "Larghezza"
      },
            padding: {
        type: "range",
        min: 10,
        max: 100,
        label: "Padding"
      },
            margin: {
        type: "range",
        min: 10,
        max: 100,
        label: "Margin"
      },
            background: {
        type: "color",
        label: "Sfondo"
      }
    },

    render(column){
      const p = column.props;
      return `
      <div class="canvas-column"
           data-id="${column.id}"
           style="width:${p.width ||100}%;
                  padding:${p.padding ||20}px;
                  margin:${p.margin ||20}px;
                  background:${p.background ||'transparent'};">
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
// console.log("COLONNA TROVATA");
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
                value="${column.width ?? 100}"
                data-column-field="width"
                data-column-input="range"
            >

            <input
                id="col-width-number"
                type="number"
                min="10"
                max="100"
                value="${column.width ?? 100}"
                data-column-field="width"
                data-column-input="number"
            >

            <label for="col-padding">Padding px</label>
            <input
                id="col-padding"
                type="number"
                min="0"
                max="100"
                value="${column.padding ?? 0}"
                data-column-field="padding"
                data-column-input="number"
            >

            <label for="col-margin">Margin px</label>
            <input
                id="col-margin"
                type="number"
                min="0"
                max="100"
                value="${column.margin ?? 20}"
                data-column-field="margin"
                data-column-input="number"
            >
            <label for="col-sfondo">Colore sfondo</label>
            <select
                id="col-sfondo"
                type="select"
                data-column-field="sfondoColor"
            >
                <option value="var(--color-primary)" ${column.sfondoColor === "var(--color-primary)" ? "selected" : ""}>Primario</option>
                <option value="var(--color-secondary)" ${column.sfondoColor === "var(--color-secondary)" ? "selected" : ""}>Secondario</option>
                <option value="var(--color-accent)" ${column.sfondoColor === "var(--color-accent)" ? "selected" : ""}>Accent</option>
                <option value="var(--color-text)" ${column.sfondoColor === "var(--color-text)" ? "selected" : ""}>Testo</option>
                <option value="var(--color-bg)" ${column.sfondoColor === "var(--color-bg)" ? "selected" : ""}>Sfondo</option>
            </select>

            <input
                id="col-sfondo-color-picker"
                type="color"
                value="${column.sfondoColor && column.sfondoColor.startsWith('#') ? column.sfondoColor : '#000000'}"
                data-column-field="sfondoColor"
            >

                
            <label for="col-border">Spessore bordo px</label>
            <input
                id="col-border"
                type="number"
                min="0"
                max="10"
                value="${column.border ?? 0}"
                data-column-field="border"
                data-column-input="number"
            >

            <label for="col-radius">Raggio bordo px</label>
            <input
                id="col-radius"
                type="number"
                min="0"
                max="100"
                value="${column.radius ?? 0}"
                data-column-field="radius"
                data-column-input="number"
            >

            <label for="col-border-style">Stile linea</label>
            <select
                id="col-border-style"
                type="select"
                data-column-field="borderStyle"
            >
                <option value="solid" ${column.borderStyle === "solid" ? "selected" : ""}>solido</option>
                <option value="dashed" ${column.borderStyle === "dashed" ? "selected" : ""}>tratteggiato</option>
                <option value="dotted" ${column.borderStyle === "dotted" ? "selected" : ""}>punteggiato</option>
            </select>

            <label for="col-border-color">Colore bordo</label>
            <select
                id="col-border-color"
                type="select"
                data-column-field="borderStyleColor"
            >
                <option value="var(--color-primary)" ${column.borderStyleColor === "var(--color-primary)" ? "selected" : ""}>Primario</option>
                <option value="var(--color-secondary)" ${column.borderStyleColor === "var(--color-secondary)" ? "selected" : ""}>Secondario</option>
                <option value="var(--color-accent)" ${column.borderStyleColor === "var(--color-accent)" ? "selected" : ""}>Accent</option>
                <option value="var(--color-text)" ${column.borderStyleColor === "var(--color-text)" ? "selected" : ""}>Testo</option>
                <option value="var(--color-bg)" ${column.borderStyleColor === "var(--color-bg)" ? "selected" : ""}>Sfondo</option>
            </select>

            <input
                id="col-border-color-picker"
                type="color"
                value="${column.borderStyleColor && column.borderStyleColor.startsWith('#') ? column.borderStyleColor : '#000000'}"
                data-column-field="borderStyleColor"
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
        .css("flex-basis", (column.width ?? 100) + "%")
        .css("padding", (column.padding ?? 0) + "px")
        .css("margin", (column.margin ?? 20) + "px")
        .css("background", column.sfondoColor || "transparent")
        .css("border-width", (column.border ?? 0) + "px")
        .css("border-style", column.borderStyle || "solid")
        .css("border-color", column.borderStyleColor || "#dddddd")
        .css("border-radius", (column.radius ?? 0) + "px")
        .css("flex-grow", 0)
        .css("flex-shrink", 0);

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