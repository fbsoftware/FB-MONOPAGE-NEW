//===============================================================
// Editor Drag & Drop
//==============================================================    
$(document).on("dragstart", ".palette-widget", function(e){

    const type = $(this).data("widget");

    e.originalEvent.dataTransfer.setData("widget-type", type);

});

//=================================
// Drop su colonna
//================================= 
$(document).on("drop", ".canvas-column", function(e){
    e.preventDefault();

    const widgetType = e.originalEvent.dataTransfer.getData("widget-type");
    if(!widgetType) return;

    const widget = editor.createWidget(widgetType);

    const columnId = $(this).data("id");
    editor.state.sections.forEach(section => {
        section.columns.forEach(column => {
            if(column.id === columnId){
                column.widgets.push(widget);
            }
        });
    });

    editor.render();
});

//=================================
// 3️⃣ Aggiorna lo state
//=================================
editor.updateWidgetOrder = function(columnId, newOrder){

    editor.state.sections.forEach(section => {

        section.columns.forEach(column => {

            if(column.id === columnId){

                const reordered = [];

                newOrder.forEach(widgetId => {

                    const widget =
                        column.widgets.find(w => w.id === widgetId);

                    if(widget) reordered.push(widget);

                });

                column.widgets = reordered;

            }

        });

    });

};

//=================================
// Colonne "droppabili"
//=================================
$(document).on("dragover", ".canvas-column", function(e){
    e.preventDefault();
});
$(document).on("dragenter", ".canvas-column", function(){
    $(this).addClass("drag-over");
});

$(document).on("dragleave", ".canvas-column", function(){
    $(this).removeClass("drag-over");
});

$(document).on("drop", ".canvas-column", function(){
    $(this).removeClass("drag-over");
});

//=================================
// widgets sortable
//=================================
editor.initSortableWidgets = function () {

    $(".canvas-column").sortable({
        items: "> .canvas-widget",
        connectWith: ".canvas-column",

        handle: ".widget-drag-handle",

        placeholder: "widget-placeholder",
        tolerance: "pointer",
        forcePlaceholderSize: true,

        start: function (event, ui) {
            console.log("SORT WIDGET START:", ui.item.data("id"));
            ui.placeholder.height(ui.item.outerHeight());
        },

        stop: function (event, ui) {
            console.log("SORT WIDGET STOP:", ui.item.data("id"));

            editor.syncWidgetsState();
            editor.state.isDirty = true;
        }
    });
};

//=================================
// Sync widget order in state
//=================================
editor.syncWidgetsState = function () {

    const widgetMap = {};

    // Salva prima tutti i widget esistenti
    editor.state.sections.forEach(section => {
        section.columns.forEach(column => {
            column.widgets.forEach(widget => {
                widgetMap[widget.id] = widget;
            });
        });
    });

    // Poi ricostruisce gli array widgets
    // leggendo il DOM corrente
    editor.state.sections.forEach(section => {

        section.columns.forEach(column => {

            const $column =
                $(".canvas-column[data-id='" + column.id + "']");

            const newWidgets = [];

            $column
                .children(".canvas-widget")
                .each(function () {

                    const widgetId = $(this).data("id");

                    if (widgetMap[widgetId]) {
                        newWidgets.push(
                            widgetMap[widgetId]
                        );
                    }
                });

            column.widgets = newWidgets;
        });
    });
};
//=================================
// Find widget by ID
//=================================
editor.findWidgetById = function (id) {

    for (const section of editor.state.sections) {

        for (const column of section.columns) {

            const widget = column.widgets.find(w => w.id === id);

            if (widget) return widget;

        }

    }

    return null;

};
