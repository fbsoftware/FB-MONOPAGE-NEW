//====================================
//  core editor functions
//====================================
var editor = editor || {};

// gruppi disponibili inspector
    editor.inspectorGroups = {
    contenuto: "Contenuto",
    stile: "Stile",
    avanzate: "Avanzate",
    immagini: "Immagini",
    overlay: "Overlay"
};

editor.inspectorGroupOrder = [
    "contenuto",
    "stile",
    "immagini",
    "overlay",
    "avanzate"
];

editor.galleryFolders = [];

//====================================
//  deseleziona se fuori sezione/inspector
//====================================
$(document).on("click", function(e){

    if(
        $(e.target).closest(".canvas-widget").length ||
        $(e.target).closest(".canvas-column").length ||
        $(e.target).closest(".canvas-section").length ||
        $(e.target).closest("#widget-inspector").length ||
        $(e.target).closest("#widgets-panel").length ||
        $(e.target).closest("#global").length ||
        $(e.target).closest("#tabs").length
    ){
        return;
    }

    editor.clearSelection();
    editor.render();
});

//=================================
//  Carica folder gallery da server
//=================================
editor.loadGalleryFolders = function(){

    const url =
        `${window.FB_APP.appUrl}/FB-EDITOR/api/gallery-folders.php?t=${Date.now()}`;

    fetch(url)
        .then(r => r.text())
        .then(text => {
            let data;

            try {
                data = JSON.parse(text);
            } catch(e) {
                console.error("Errore parsing JSON:", e);
                return;
            }

            if (!data.success) return;

            editor.galleryFolders = data.folders;

        })
        .catch(err =>
            console.error("Errore fetch gallery folders:", err)
        );
};

//console.log("CORE: chiamo loadGalleryFolders");
//editor.loadGalleryFolders();
//=================================
// risolve path immagini
//=================================
    editor.resolveAssetUrl = function(path){
    if(!path) return "";

    if(
        path.startsWith("http://") ||
        path.startsWith("https://") ||
        path.startsWith("/")
    ){
        return path;
    }

    const appUrl = window.FB_APP?.appUrl || "";

    return appUrl + "/" + path.replace(/^\/+/, "");
};

//=================================
// Applica CSS variables globali
//=================================
editor.applyGlobalCssVariables = function(config){

    if(!config || !config.colors) return;

    const root = document.documentElement;

    root.style.setProperty(
        "--color-primary",
        config.colors.primary || "#3366ff"
    );

    root.style.setProperty(
        "--color-secondary",
        config.colors.secondary || "#ff6633"
    );

    root.style.setProperty(
        "--color-accent",
        config.colors.accent || "#ffa500"
    );

    root.style.setProperty(
        "--color-text",
        config.colors.text || "#222222"
    );

    root.style.setProperty(
        "--color-bg",
        config.colors.bg || "#ffffff"
    );
};

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

    fetch(window.APP_URL + "/save.php", {
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

    window.location.href = "APP_URL/admin/admin.php";
});

//=================================
// carica la configurazione globale (colori, font, ecc) da site-config.json
//=================================
editor.applySiteConfigToForm = function(config){
    $("#color-primary").val(config.colors?.primary ?? "#3366ff");
    $("#color-secondary").val(config.colors?.secondary ?? "#ff6633");
    $("#color-accent").val(config.colors?.accent ?? "#ffa500");
    $("#color-text").val(config.colors?.text ?? "#222222");
    $("#color-bg").val(config.colors?.bg ?? "#ffffff");

    $("#heading-family").val(config.typography?.heading?.fontFamily ?? "Inter");
    $("#heading-weight").val(config.typography?.heading?.weight ?? 400);

    $("#body-family").val(config.typography?.body?.fontFamily ?? "Inter");
    $("#body-weight").val(config.typography?.body?.weight ?? 400);

    $("#font-h1").val(config.typography?.sizes?.h1 ?? 48);
    $("#font-h2").val(config.typography?.sizes?.h2 ?? 36);
    $("#font-h3").val(config.typography?.sizes?.h3 ?? 28);
    $("#font-body").val(config.typography?.sizes?.body ?? 16);
    $("#font-small").val(config.typography?.sizes?.small ?? 14);
};

//=================================
//  Bind site config save
//=================================
editor.bindSiteConfigSave = function(){

    $("#saveSiteConfig").off("click").on("click", function(){

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
                    weight: parseInt($("#heading-weight").val(), 10)
                },

                body: {
                    fontFamily: $("#body-family").val(),
                    weight: parseInt($("#body-weight").val(), 10)
                },

                sizes: {
                    h1: parseInt($("#font-h1").val(), 10),
                    h2: parseInt($("#font-h2").val(), 10),
                    h3: parseInt($("#font-h3").val(), 10),
                    body: parseInt($("#font-body").val(), 10),
                    small: parseInt($("#font-small").val(), 10)
                }
            }
        };

        fetch(window.APP_URL + "/editor/save-site-config.php", {
            method: "POST",
            headers: {"Content-Type":"application/json"},
            body: JSON.stringify(config)
        })
        .then(res => res.json())
        .then(res => {
            editor.applyGlobalCssVariables(config);
            if(res.ok){
                $("#siteConfigStatus").text("✔ Salvato");

                window.SITE_CONFIG = config;
                editor.applySiteConfigToForm(config);
                editor.applyGlobalCssVariables(config);
                editor.render();

                console.log("Configurazione aggiornata", config);
                alert("Configurazione salvata!");
            }
        });
    });
};

//=================================
// Toolbar Cancella colonna
//=================================
$(document).on("click", ".delete-column, .delete-column *", function(e){

    e.preventDefault();
    e.stopPropagation();

    const $btn = $(this).closest(".delete-column");
    const colId = $btn.closest(".canvas-column").data("id");

    console.log("DELETE-C", colId);

    if(!colId) return;

    editor.deleteColumn(colId);
});

//=================================
// Selezione colonna
//=================================
$(document).on("click", ".canvas-column", function(e){
    e.stopPropagation();
    
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
$(document).on("input change", "#widget-inspector [data-field]", function(){
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

    // Caso speciale: navbar.items è JSON
    if (field === "items") {

    if (!$(this).is("textarea")) {
        return;
    }

    try {
        item.props[field] = JSON.parse(value);
    } catch (e) {
        console.warn("JSON voci menu non valido", value, e);
        return;
    }

    editor.render();
    return;
}

    item.props[field] = value;

    editor.render(); // refresh canvas
});

//=======================================
//  Gestione modifica voci menu (navbar)
//=======================================
$(document).on("input change", ".menu-item-row [data-menu-item-field]", function(){

    const widget = editor.findWidgetById(editor.state.selectedId);
    if (!widget || widget.type !== "navbar") return;

    const index = parseInt($(this).closest(".menu-item-row").attr("data-index"), 10);
    const field = $(this).attr("data-menu-item-field");
    const value = $(this).val();

    widget.props.items[index][field] = value;

    editor.state.isDirty = true;
    editor.render();
});

//=======================================
//  Gestione cancellazione voce menu (navbar)
//=======================================
$(document).on("click", ".menu-item-row [data-menu-item-delete]", function(){

    const widget = editor.findWidgetById(editor.state.selectedId);
    if (!widget || widget.type !== "navbar") return;

    const index = parseInt($(this).closest(".menu-item-row").attr("data-index"), 10);

    widget.props.items.splice(index, 1);

    editor.state.isDirty = true;
    editor.render();
    editor.renderInspector(widget, editor.widgets[widget.type]);
});
//=======================================
//  Gestione aggiunta voce menu (navbar)
//=======================================
$(document).on("click", "[data-menu-item-add]", function(){

    const widget = editor.findWidgetById(editor.state.selectedId);
    if (!widget || widget.type !== "navbar") return;

    if (!widget.props) widget.props = {};
    if (!Array.isArray(widget.props.items)) widget.props.items = [];

    const $box = $(this).closest(".menu-item-new");

    const label = $box.find('[data-menu-new-field="label"]').val();
    const type = $box.find('[data-menu-new-field="type"]').val();
    const target = $box.find('[data-menu-new-field="target"]').val();

    if (!label || !target) {
        alert("Inserisci almeno Label e Target");
        return;
    }

    widget.props.items.push({
        label: label,
        type: type,
        target: target
    });

    editor.state.isDirty = true;
    editor.render();
    editor.renderInspector(widget, editor.widgets[widget.type]);
});

//=======================================
//  Gestione spostamento voce menu su (navbar)
//=======================================
$(document).on("click", "[data-menu-item-up]", function(){

    const widget = editor.findWidgetById(editor.state.selectedId);
    if (!widget || widget.type !== "navbar") return;

    const index = parseInt(
        $(this).closest(".menu-item-row").attr("data-index"),
        10
    );

    if (index <= 0) return;

    const items = widget.props.items;

    [items[index - 1], items[index]] =
    [items[index], items[index - 1]];

    editor.state.isDirty = true;

    editor.render();
    editor.renderInspector(widget, editor.widgets[widget.type]);
});

//=======================================
//  Gestione spostamento voce menu giù (navbar)
//=======================================
$(document).on("click", "[data-menu-item-down]", function(){

    const widget = editor.findWidgetById(editor.state.selectedId);
    if (!widget || widget.type !== "navbar") return;

    const index = parseInt(
        $(this).closest(".menu-item-row").attr("data-index"),
        10
    );

    const items = widget.props.items;

    if (index >= items.length - 1) return;

    [items[index + 1], items[index]] =
    [items[index], items[index + 1]];

    editor.state.isDirty = true;

    editor.render();
    editor.renderInspector(widget, editor.widgets[widget.type]);
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
//  TABS
//=============================================
$("#tabs").on("tabsactivate", function(event, ui){

    if(ui.newPanel.attr("id") === "global"){
        editor.applySiteConfigToForm(window.SITE_CONFIG);
    }
});

//=================================
//  setta lo stato s 'isDirty' a true 
//  quando si modifica un campo nell'inspector
//=================================
$(document).on("mousedown click input change", "#editor-tabs input, #editor-tabs select, #editor-tabs textarea, #editor-tabs button", 
    function(e){
        e.stopPropagation();
        editor.state.isDirty = true;

    }
);

//=================================
//  Apri il tab dei dettagli
//=================================
editor.openDetailsTab = function(){
    $("#tabs").tabs("option", "active", 1); // cambia in 2 se Dettagli è il terzo tab
    $("#widget-inspector").show();
};
//=============================================
// SELEZIONE / DESELEZIONE CENTRALIZZATA
//=============================================
editor.clearSelection = function(){ 
    editor.state.selectedType = null; 
    editor.state.selectedId = null; };

editor.selectSection = function(id){
    editor.clearSelection();
    editor.state.selectedType = "section";
    editor.state.selectedId = id;
    editor.render();
    editor.openSectionInspector(id);
    editor.openDetailsTab();
};

editor.selectColumn = function(id){
    editor.clearSelection();
    editor.state.selectedType = "column";
    editor.state.selectedId = id;
    editor.render();
    editor.openColumnInspector(id);
    editor.openDetailsTab();
};

editor.selectWidget = function(id){
    editor.clearSelection();
    editor.state.selectedType = "widget";
    editor.state.selectedId = id;
    editor.render();
    editor.openWidgetInspector(id);
    editor.openDetailsTab();
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

    const res = await fetch(window.APP_URL + "/FB-EDITOR/api/list-images.php");
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
$(document).on("click", ".image-thumb", function(e){
    e.preventDefault();
    e.stopPropagation();

    const field = $(this).data("field");
    const value = $(this).data("value");

    const selectedType = editor.state.selectedType;
    const selectedId = editor.state.selectedId;

    console.log("selectedType", selectedType);
    console.log("selectedId", selectedId);
    console.log("field", field, "value", value);

    if(selectedType === "widget"){
        const widget = editor.findWidgetById(selectedId);
        if(!widget) return;

        widget.props[field] = value;

        editor.state.isDirty = true;
        editor.render();
        editor.openInspector(widget, editor.widgets[widget.type]);
        return;
    }

    if(selectedType === "section"){
        const section = editor.findSectionById(selectedId);
        if(!section) return;

        section[field] = value;

        editor.state.isDirty = true;
        editor.render();
        editor.openSectionInspector(section.id);
        return;
    }

    if(selectedType === "column"){
        const column = editor.findColumnById(selectedId);
        if(!column) return;

        column[field] = value;

        editor.state.isDirty = true;
        editor.render();
        editor.openColumnInspector(column.id);
        return;
    }
});


//=================================
// blocca la propagazione sull’intero picker:
//================================= 
$(document).on("click", ".image-picker, .image-thumb, .image-thumb *", function(e){
    e.stopPropagation();
});

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

const res = await fetch(window.APP_URL + "/FB-EDITOR/api/save-section-template.php", {        method: "POST",
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

const res = await fetch(window.APP_URL + "/FB-EDITOR/api/list-section-templates.php");    const data = await res.json();

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


//=================================
//  Duplica widget
//=================================
$(document).on("click", ".duplicate-widget, .duplicate-widget *", function(e){
    e.preventDefault();
    e.stopPropagation();

    const widgetId = $(this)
        .closest(".canvas-widget")
        .data("id");

    if(!widgetId) return;

    let foundColumn = null;
    let foundIndex = -1;

    (editor.state.sections || []).forEach(section => {
        (section.columns || []).forEach(column => {
            const index = (column.widgets || []).findIndex(w => w.id === widgetId);

            if(index !== -1){
                foundColumn = column;
                foundIndex = index;
            }
        });
    });

    if(!foundColumn || foundIndex === -1) return;

    const original = foundColumn.widgets[foundIndex];
    const clone = editor.cloneWidget(original);

    foundColumn.widgets.splice(foundIndex + 1, 0, clone);

    editor.state.selectedType = "widget";
    editor.state.selectedId = clone.id;
    editor.state.isDirty = true;

    editor.render();

    const def = editor.widgets[clone.type];
    editor.openInspector(clone, def);
});

//=================================
// crea overlay una volta sola
//=================================
if(!document.querySelector('.gallery-lightbox-overlay')){
    const overlay = document.createElement('div');
    overlay.className = 'gallery-lightbox-overlay';
    overlay.innerHTML = '<img src="" alt="Immagine gallery">';
    document.body.appendChild(overlay);

    // click sull'overlay chiude la lightbox
    overlay.addEventListener('click', () => {
        overlay.classList.remove('visible');
    });
}

//=================================
// listener globale sulle immagini gallery
//=================================
document.addEventListener('click', function(e){
    if(e.target.classList.contains('gallery-lightbox-trigger')){
        const src = e.target.dataset.src;
        const overlay = document.querySelector('.gallery-lightbox-overlay');
        overlay.querySelector('img').src = src;
        overlay.classList.add('visible');
    }
});

//======================================
//  Gestione checkbox inspector
//======================================
$(document).on("change", "#widget-inspector input[type='checkbox'][data-field]",
    function () {

        const field = $(this).data("field");
        const value = this.checked;

        const widget = editor.findWidgetById(editor.state.selectedId);

        if (!widget || editor.state.selectedType !== "widget") {
            return;
        }

        widget.props = widget.props || {};
        widget.props[field] = value;

        editor.state.isDirty = true;

        console.log("CHECKBOX UPDATE:", field, value);

        editor.render();

        // Il render ricrea il canvas: inizializza nuovamente gli slider.
        setTimeout(function () {
            document.querySelectorAll(".widget-slider").forEach(function (slider) {
                editor.initSliderWidget(slider);
            });
        }, 0);
    }
);

//======================================
//  Gestione modifica valori inspector (Escluso checkbox )
//======================================
$(document).on("input change","#widget-inspector [data-field]",
    function () {

        if ($(this).is(":checkbox")) {
            return;
        }

        const field = $(this).data("field");
        const value = $(this).val();

        const widget = editor.findWidgetById(editor.state.selectedId);

        if (!widget || editor.state.selectedType !== "widget") {
            return;
        }

        widget.props = widget.props || {};
        widget.props[field] = value;

        editor.state.isDirty = true;

        if (
            field === "folder" &&
            (widget.type === "gallery" || widget.type === "slide")
        ) {
            editor.loadGalleryImages(value);
            return;
        }

        editor.render();
    }
);
//==================================
//  LETTURA BOOLEANI
//==================================
editor.toBoolean = function(value, defaultValue = false) {

    if (value === undefined || value === null || value === "") {
        return defaultValue;
    }

    return (
        value === true ||
        value === 1 ||
        value === "1" ||
        value === "true"
    );
};

// ==========================
// Inizializza comportamento slider
// ==========================
editor.initSliderWidget = function(slider) {

    if (!slider) return;

    const slides = Array.from(
        slider.querySelectorAll(".slide")
    );

    const dots = Array.from(
        slider.querySelectorAll(".dot")
    );

    const nextBtn = slider.querySelector(".next");
    const prevBtn = slider.querySelector(".prev");

    if (!slides.length) return;

    let current = 0;

    /*
     * Se il DOM contiene già una slide active,
     * parte da quella.
     */
    const activeIndex = slides.findIndex(slide =>
        slide.classList.contains("active")
    );

    if (activeIndex >= 0) {
        current = activeIndex;
    }

    function showSlide(index) {

        /*
         * Riporta sempre l'indice entro i limiti.
         */
        if (index >= slides.length) {
            index = 0;
        }

        if (index < 0) {
            index = slides.length - 1;
        }

        slides.forEach((slide, i) => {
            slide.classList.toggle("active", i === index);
        });

        dots.forEach((dot, i) => {
            dot.classList.toggle("active", i === index);
        });

        current = index;
    }

    /*
     * Evita di accumulare timer.
     */
    if (slider._intervalId) {
        clearInterval(slider._intervalId);
        slider._intervalId = null;
    }

    if (nextBtn) {
        nextBtn.onclick = function(event) {
            event.preventDefault();
            event.stopPropagation();

            showSlide(current + 1);
        };
    }

    if (prevBtn) {
        prevBtn.onclick = function(event) {
            event.preventDefault();
            event.stopPropagation();

            showSlide(current - 1);
        };
    }

    dots.forEach(dot => {

        dot.onclick = function(event) {
            event.preventDefault();
            event.stopPropagation();

            const index = parseInt(
                dot.dataset.slide,
                10
            );

            if (!Number.isNaN(index)) {
                showSlide(index);
            }
        };
    });

    const interval = parseInt(
        slider.dataset.interval || "5000",
        10
    );

    if (
        Number.isFinite(interval) &&
        interval > 0 &&
        slides.length > 1
    ) {
        slider._intervalId = setInterval(function() {
            showSlide(current + 1);
        }, interval);
    }

    showSlide(current);
};

// ==========================
// Inizializza tutti gli slider nel canvas
// ==========================
editor.initAllSliders = function() {

    document
        .querySelectorAll(".widget-slider")
        .forEach(function(slider) {
            editor.initSliderWidget(slider);
        });
};

//=================================
//  RENDER STRUTTURA PAGINA
//=================================
editor.renderPageStructure = function () {

    const $container = $("#page-structure");

    if (!$container.length) {
        return;
    }

    const sections = editor.state?.sections || [];

    if (!sections.length) {
        $container.html(
            '<div class="structure-empty">Nessuna sezione</div>'
        );
        return;
    }

    let html = "";

    sections.forEach(function(section, sectionIndex) {

        html += `
            <div class="structure-section">

                <div class="structure-row structure-section-row"
                     data-structure-type="section"
                     data-structure-id="${section.id}">
                     Sezione ${sectionIndex + 1}
                </div>
        `;

        const columns = section.columns || [];

        columns.forEach(function(column, columnIndex) {

            html += `
                <div class="structure-row structure-column-row"
                     data-structure-type="column"
                     data-structure-id="${column.id}">
                     Colonna ${columnIndex + 1}
                </div>
            `;

            const widgets = column.widgets || [];

            widgets.forEach(function(widget) {

                const def = editor.widgets[widget.type];

                const label =
                    def?.label ||
                    widget.type ||
                    "Widget";

                html += `
                    <div class="structure-row structure-widget-row"
                         data-structure-type="widget"
                         data-structure-id="${widget.id}">
                         ${label}
                    </div>
                `;
            });

            html += `
                </div>
            `;
        });

        html += `
            </div>
        `;
    });

    $container.html(html);
};


//=================================
// Selezione dalla struttura pagina
//=================================
$(document).on(
    "click",
    "#page-structure [data-structure-type]",
    function (event) {

        event.stopPropagation();

        const type = $(this).attr("data-structure-type");
        const id = $(this).attr("data-structure-id");

        console.log(
            "STRUTTURA SELECT:",
            type,
            id
        );

        editor.state.selectedType = type;
        editor.state.selectedId = id;

        editor.render();

        if (type === "widget") {
            editor.openWidgetInspector(id);
        }

        if (type === "column") {
            editor.openColumnInspector(id);
        }

        if (type === "section") {
            editor.openSectionInspector(id);
        }

        // apre automaticamente il tab Dettagli
        $("#tabs > ul > li").eq(1).find("a").trigger("click");
    }
);