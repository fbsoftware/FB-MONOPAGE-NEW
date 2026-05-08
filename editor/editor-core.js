//====================================
//  deseleziona se fuori sezione/inspector
//====================================
$(document).on("click", function(e){

    if(
        $(e.target).closest(".canvas-widget").length ||
        $(e.target).closest(".canvas-column").length ||
        $(e.target).closest(".canvas-section").length ||
        $(e.target).closest("#inspector").length
    ){
        return;
    }

    editor.clearSelection();
    editor.render();
});


//=================================
// Move section up
//=================================
$(document).on("click", ".move-up", function(e) {
    e.stopPropagation();

    const sectionId = $(this)
        .closest(".canvas-section")
        .data("id");

    editor.moveSection(sectionId, "up");

});

//=================================
// Move section down
//=================================
$(document).on("click", ".move-down", function(e) {

    e.stopPropagation();

    const sectionId = $(this)
        .closest(".canvas-section")
        .data("id");

    editor.moveSection(sectionId, "down");

});
//=================================
// Delete section
//=================================
$(document).on("click", ".delete-section", function(e){

    e.stopPropagation();

    const sectionId = $(this)
        .closest(".canvas-section")
        .data("id");

  editor.deleteSection(sectionId);

});

//=================================
// Duplicate section
//=================================
$(document).on("click", ".duplicate", function(e){

    e.stopPropagation();

    const sectionId = $(this)
        .closest(".canvas-section")
        .data("id");

     editor.duplicateSection(sectionId);

});

///================================
// SAVE: BOTTONE PUBBLICA
//================================
$(document).on("click", "#save-layout", function(){
    if(!confirm("Vuoi Pubblicare il layout ?")) return;

    console.log("SALVATAGGIO:", editor.state);

    fetch("save.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(editor.state)
    })
    .then(async res => {
        const text = await res.text();
        console.log("RAW SAVE RESPONSE:", text);

        try {
            return JSON.parse(text);
        } catch (err) {
            throw new Error("Risposta non JSON da save.php: " + text);
        }
    })
    .then(res => {
        console.log("SAVE RESULT:", res);

        if(res.success){
            alert("Salvato!");
        } else {
            alert("Errore: " + res.error);
        }
    })
    .catch(err => {
        console.error(err);
        alert("Errore salvataggio: " + err.message);
    });
});
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//================================
// SAVE / PUBBLICA
//================================
$(document).on("click", "#save-HTML", function(){
if(!confirm("Vuoi creare HTML ?")) return;

editor.state.page = editor.state; // copia lo state corrente in page
const html = editor.renderPageHTML(editor.state.page);
downloadHTML(html);

})

//===========================================
//  EXIT da editor
//===========================================
$(document).on("click", "#editor-exit", function(){

    if(editor.state.isDirty){

        const ok = confirm("Vuoi uscire senza salvare?");

        if(!ok) return;
    }

    window.location.href = "/FB-JSON/admin/admin.php";
});
//=================================
//  Load site config
//=================================
editor.loadSiteConfig = function() {

$("#saveSiteConfig").on("click", function(){

    const config = {

        colors: {
            primary: $("#color-primary").val(),
            secondary: $("#color-secondary").val(),
            accent: $("#color-accent").val(),
            text: $("#color-text").val(),
            bg: $("#color-bg").val()
        },

        typography: {

            heading: {
                fontFamily: $("#heading-family").val(),
                weight: parseInt($("#heading-weight").val())
            },

            body: {
                fontFamily: $("#body-family").val(),
                weight: parseInt($("#body-weight").val())
            },

            sizes: {
                h1: parseInt($("#font-h1").val()),
                h2: parseInt($("#font-h2").val()),
                h3: parseInt($("#font-h3").val()),
                body: parseInt($("#font-body").val()),
                small: parseInt($("#font-small").val())
            }

        }

    };

    fetch("save-site-config.php", {
        method: "POST",
        headers: {"Content-Type":"application/json"},
        body: JSON.stringify(config)
    })
    .then(res=>res.json())
    .then(res=>{

        if(res.ok){

            $("#siteConfigStatus").text("✔ Salvato");

            window.SITE_CONFIG = config;

            editor.loadSiteConfig();

            console.log("Configurazione aggiornata", config);

        }

    });

});
};


//=================================
// Selezione colonna
//=================================
$(document).on("click", ".canvas-column", function(e){
    e.stopPropagation();
 console.log("CLICCATA COLONNA");
if($(e.target).closest(".canvas-widget").length) return;
    const id = $(this).data("id");

    editor.selectColumn(id);
});

//=================================
//  Aggiungi colonna, da sezione
//=================================
$(document).on("click", ".add-column", function(e){

    e.stopPropagation();

    const sectionId = $(this)
        .closest(".canvas-section")
        .data("id");

    const section = editor.state.sections.find(sec => sec.id === sectionId);

    if(!section){
        console.error("Sezione non trovata:", sectionId);
        return;
    }

    section.columns.push({
        id: editor.utils.uuid("col"),
        width: 100  , 
        margin: 20,
        widgets: []
    });

    editor.render();
});

//=================================
//  Toolbar Cancella colonna
//=================================
$(document).on("click",".delete-column",function(e){

    e.stopPropagation();

    const colId = $(this)
        .closest(".canvas-column")
        .data("id");

    editor.deleteColumn(colId);

});


//======================================
// clic-widget per selezione
//======================================
$(document).on("click", ".canvas-widget", function(e){
    e.stopPropagation();

    const id = $(this).data("id");

    editor.selectWidget(id);
});
//======================================
// Cancella widget 
//======================================
$(document).on("click", ".widget-delete", function(e){

    e.stopPropagation();

    const id = $(this).closest(".canvas-widget").data("id");

    editor.state.sections.forEach(section=>{
        section.columns.forEach(column=>{
            column.widgets = column.widgets.filter(w => w.id !== id);
        });
    });

    editor.render();

});

//===============================
//  3️⃣ Gestione modifica valori 
//===============================
$(document).on("input change", "#inspector [data-field]", function(){

    const field = $(this).data("field");
    const value = $(this).val();
    editor.state.isDirty = true;

    let item;

    if (editor.state.selectedType === "widget"){
        item = editor.findWidgetById(editor.state.selectedId);
    }

    if (editor.state.selectedType === "column"){
        item = editor.findColumnById(editor.state.selectedId);
    }

    if (!item) return;

    if (!item.props) item.props = {};

    item.props[field] = value;

    editor.render(); // refresh canvas
});

//=======================================
//  valori globali
//=======================================
editor.globals = {

    colors:{
        primary:"var(--color-primary)",
        secondary:"var(--color-secondary)",
        accent:"var(--color-accent)",
        bg:"var(--color-bg)",
        text:"var(--color-text)"
    },

    align:{
        left:"sinistra",
        center:"centro",
        justify:"giustificato",
        right:"destra"
    }

};

//=============================================
//  SELEZIONE/DESELEZIONE CENTRALIZZATA
//=============================================
$(document).on("click", ".canvas-section", function(e){
    e.stopPropagation();

  if($(e.target).closest(".canvas-column").length) return;

    const id = $(this).data("id");
    editor.selectSection(id);
 
});

//=============================================
//  BLOCCHI VARI
//=============================================
$(document).on("click", "#editor-tabs, #tab-details, #inspector", function(e){
    e.stopPropagation();
});


$(document).on("mousedown click input change", "#editor-tabs input, #editor-tabs select, #editor-tabs textarea, #editor-tabs button",
    function(e){
        e.stopPropagation();
        editor.state.isDirty = true;

    }
);

//========================================
//  Blocca direttamente l'input inspector
//========================================
$(document).on("mousedown click", "#inspector, #inspector *",
    function(e){
        e.stopPropagation();
    }
);

//=============================================
// SELEZIONE / DESELEZIONE CENTRALIZZATA
//=============================================
editor.clearSelection = function(){
    editor.state.selectedType = null;
    editor.state.selectedId = null;
};

editor.selectSection = function(id){
    editor.clearSelection();
    editor.state.selectedType = "section";
    editor.state.selectedId = id;
    editor.render();
    editor.openSectionInspector(id);
};

editor.selectColumn = function(id){
    editor.clearSelection();
    editor.state.selectedType = "column";
    editor.state.selectedId = id;
    editor.render();
    editor.openColumnInspector(id);
};

editor.selectWidget = function(id){
    editor.clearSelection();
    editor.state.selectedType = "widget";
    editor.state.selectedId = id;
    editor.render();
    editor.openWidgetInspector(id);
};

//=================================
// data-upload-image    
//=================================
$(document).on("change", '#inspector input[data-upload-image="1"]', function(){
    editor.state.isDirty = true;

    const file = this.files[0];
    if(!file) return;

    const formData = new FormData();
    formData.append("image", file);

    fetch("upload.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        console.log("UPLOAD RESULT:", res);

        if(!res.success){
            alert("Errore upload: " + res.error);
            return;
        }

        const widgetId = editor.state.selectedId;
        const widget = editor.findWidgetById(widgetId);

        if(!widget) return;

        widget.props.src = res.path;

        editor.render();
        editor.openWidgetInspector(widgetId);
    })
    .catch(err => {
        console.error(err);
        alert("Errore upload immagine");
    });
});

//=================================
//  lettura immagini    
//=================================
editor.loadImages = async function(){

    const res = await fetch("/FB-JSON/api/list-images.php");
    const data = await res.json();

    if(!data.success){
        console.error("Errore immagini:", data.error);
        return [];
    }

    return data.images;
};

//=================================
// image-thumb click
//=================================
//console.log("HANDLER image-thumb caricato");
$(document).on("click", ".image-thumb", function(e){
    e.preventDefault();
    e.stopPropagation();

    const field = $(this).data("field");
    const value = $(this).data("value");

    const widgetId = editor.state.selectedId;
    const widget = editor.findWidgetById(widgetId);

    console.log("selectedId", widgetId);
    console.log("widget", widget);
    console.log("field", field, "value", value);

    if(!widget) return;

    widget.props[field] = value;

    editor.render();
    editor.openInspector(widget, editor.widgets[widget.type]);
});

//=================================
// blocca la propagazione sull’intero picker:
//================================= 
$(document).on("click", ".image-picker, .image-thumb, .image-thumb *", function(e){
    e.stopPropagation();
});
//=================================
///  risolve path immagini
//=================================
editor.resolveAssetUrl = function(path){
    if(!path) return "";
    if(path.startsWith("http://") || path.startsWith("https://") || path.startsWith("/")){
        return path;
    }
    return "/FB-JSON/" + path.replace(/^\/+/, "");
};

//=================================
//  accordion inspector
//=================================
$(document).on("click", ".accordion-title", function(){
    $(this)
        .closest(".inspector-accordion")
        .toggleClass("open")
        .find(".accordion-content")
        .slideToggle(120);
});

//=================================
//  Protezione extra (chiusura tab)
//=================================
window.addEventListener("beforeunload", function(e){

    if(editor.state.isDirty){
        e.preventDefault();
        e.returnValue = "";
    }
});

//=================================
//  Salva sezione come template
//=================================
$(document).on("click", ".save-section-template", async function(e){
    e.preventDefault();
    e.stopPropagation();

    const sectionId = $(this).closest(".canvas-section").data("id");
    const section = editor.findSectionById(sectionId);

    if(!section) return;

    const name = prompt("Nome template sezione:");
    if(!name) return;

    const res = await fetch("/FB-JSON/api/save-section-template.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            name: name,
            section: section
        })
    });

    const data = await res.json();

    if(!data.success){
        alert("Errore salvataggio template: " + data.error);
        return;
    }

    alert("Template sezione salvato");
});

//=================================
//  Calica lista dei template
//=================================
$(document).on("click", ".add-template", async function(){

    const res = await fetch("/FB-JSON/api/list-section-templates.php");
    const data = await res.json();

    if(!data.success || !data.templates.length){
        alert("Nessun template disponibile");
        return;
    }
console.log('CLICCATO + TEMPLATE' , data);// FB: log

    let html = "<div class='template-picker'>";

    data.templates.forEach(t => {
        html += `
            <div class="template-item" data-file="${t.file}">
                📦 ${t.name}
            </div>
        `;
    });

    html += "</div>";

    $("#template-modal .content").html(html);
    $("#template-modal").show();
});

//🔴 4. Click su template → inserisci
$(document).on("click", ".template-item", function(){

    const file = $(this).data("file");

    editor.insertSectionTemplate(file);

    $("#template-modal").hide();
});

//⚫ 5. Chiudi modal
$(document).on("click", ".close-template, #template-modal .overlay", function(){
    $("#template-modal").hide();
});