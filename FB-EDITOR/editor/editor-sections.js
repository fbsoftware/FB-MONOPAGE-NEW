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
            backgroundType: "empty",
            backgroundImage: "",
            backgroundSlider: "",
            backgroundSection: "",
            backgroundGallery: "",
            slides: [],
            sliderInterval: 5,
            sliderFade: 1,
            backgroundPositionY: 50,
            overlayColor: "#000000",
            overlayOpacity: 0.4,
            height: 100,
            padding: 0,
            margin: 0,
            customCss: ""
        },

        fields: {



        backgroundType: {
                type: "select",
                label: "Tipo sfondo",
                group: "stile",
                options: {
                    empty: "Sfondo vuoto",
                    image: "Sfondo immagine overlay",
                    gallery: "Sfondo gallery overlay",
                    section: "Sfondo sezione overlay"
                }
            }, 

            backgroundSection: {
                type: "sectionFolderSelect",
                label: "Cartella sezioni",
                group: "immagini",
                showWhen: {
                    field: "backgroundType",
                    values: ["section"]
                }
},

            background:{ 
            type:"color", 
            label:"Colore", 
            group:"stile" 
            },
          

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

            backgroundSlider: {
                type: "text",
                label: "Cartella gallery",
                group: "immagini",
                showWhen: {
                    field: "backgroundType",
                    values: ["section"]
                }
            },

            backgroundGallery: {
                type: "galleryFolderSelect",
                label: "Cartella gallery",
                group: "immagini",
                showWhen: {
                    field: "backgroundType",
                    values: ["gallery"]
                }
            },
            sliderInterval: {
                type: "number",
                min: 1,
                max: 60,
                step: 1,
                label: "Intervallo immagini sec",
                group: "immagini",
                showWhen: {
                    field: "backgroundType",
                    values: ["gallery"]
                }
            },

            sliderFade: {
                type: "number",
                min: 0,
                max: 5,
                step: 0.5,
                label: "Durata dissolvenza sec",
                group: "immagini",
                showWhen: {
                    field: "backgroundType",
                    values: ["gallery"]
                }
            },

            backgroundImage: {
                type: "image_picker",
                label: "Immagine sfondo",
                group: "immagini",
                showWhen: {
                    field: "backgroundType",
                    values: ["image"]
                }
            },

            backgroundPositionY: {
                type: "number",
                min: 0,
                max: 100,
                label: "Posizione verticale immagine",
                group: "overlay",
                showWhen: {
                    field: "backgroundType",
                    values: ["image"]
                }
            },

            overlayColor: {
                type: "color",
                label: "Colore overlay",
                group: "overlay",
                showWhen: {
                    field: "backgroundType",
                    values: ["image"]
                }
            },

            overlayOpacity: {
                type: "number",
                min: 0,
                max: 1,
                step: 0.1,
                label: "Opacità overlay",
                group: "overlay",
                showWhen: {
                    field: "backgroundType",
                    values: ["image"]
                }
            },
            height: {
                type: "number",
                min: 100,
                max: 900,
                label: "Altezza sezione",
                group: "stile"
            }

        }
    }
};

//=================================
// RENDER INSPECTOR SEZIONI
//=================================
editor.renderSectionInspector = function(section){

    if(!section) return;

    const fields = editor.sections.section.fields;

    editor.renderElementInspector(
        "<h3 style='text-align:center; margin:10px;'>Dettagli Sezione</h3>",
        section,
        fields,
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


    //=================================
    // Cambio tipo sfondo
    //=================================
    if(field === "backgroundType"){

        // Cambiando modalità, elimina
        // le proprietà specifiche della modalità precedente
        section.backgroundImage = "";
        section.backgroundGallery = "";
        section.backgroundSection = "";

    }


    section[field] = value;

    editor.state.isDirty = true;

    editor.render();


    //=================================
    // Ricostruisce Inspector
    //=================================
    if(field === "backgroundType"){
        editor.renderSectionInspector(section);
    }

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
editor.renderSection = function(section, allowSlider = true){

    const selected =
        editor.state.selectedType === "section" &&
        section.id === editor.state.selectedId
        ? "selected"
        : "";

    const bgImage = section.backgroundImage
        ? `url('${editor.resolveAssetUrl(section.backgroundImage)}')`
        : "none";

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
        .css("background-position-y",
            (section.backgroundPositionY ?? 50) + "%")

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

            <button class="save-section-global button" title="Salva globale">
                <span class="material-symbols-outlined">language</span>
            </button>
        `);

    $section.prepend($toolbar);


    //=================================
    // Overlay immagine
    //=================================
    if(section.backgroundImage){

        const $overlay = $("<div>")
            .addClass("section-overlay")
            .css("position", "absolute")
            .css("inset", 0)
            .css("background", section.overlayColor || "#000")
            .css("opacity", section.overlayOpacity ?? 0.4)
            .css("pointer-events", "none")
            .css("z-index", 1);

        $section.append($overlay);
    }


    //=================================
    // Colonne della sezione
    //=================================
    const $columns = $("<div>")
        .addClass("section-columns")
        .css("position", "relative")
        .css("z-index", 2);

    (section.columns || []).forEach(col => {
        $columns.append(editor.renderColumn(col));
    });

    $section.append($columns);

//=================================
// Preview Gallery nel Canvas
//=================================
if (
    section.backgroundType === "gallery" &&
    section.backgroundGallery
) {

    const folder =
        section.backgroundGallery.replace(/^\/+|\/+$/g, "");

    fetch(
        `${window.FB_APP.appUrl}/FB-EDITOR/api/list-gallery-images.php?folder=${encodeURIComponent(folder)}`
    )
        .then(response => response.json())
        .then(data => {

            if (
                !data.success ||
                !Array.isArray(data.images) ||
                !data.images.length
            ) {
                console.warn(
                    "Nessuna immagine Gallery trovata:",
                    folder
                );
                return;
            }

            //=================================
            // Contenitore Gallery
            //=================================
            const $galleryPreview = $("<div>")
                .addClass("canvas-section-gallery-preview")
                .css({
                    position: "absolute",
                    inset: 0,
                    overflow: "hidden",
                    "z-index": 0,
                    "pointer-events": "none"
                });

            //=================================
            // Crea le immagini sovrapposte
            //=================================
            data.images.forEach(function(image, index){

                const imageUrl =
                    image.startsWith("http") || image.startsWith("/")
                        ? image
                        : `${window.FB_APP.appUrl}/${image}`;

                const $slide = $("<div>")
                    .addClass("canvas-section-gallery-slide")
                    .css({
                        position: "absolute",
                        inset: 0,
                        "background-image": `url("${imageUrl}")`,
                        "background-size": "cover",
                        "background-position": "center",
                        "background-repeat": "no-repeat",
                        opacity: index === 0 ? 1 : 0,
                        transition:
                            "opacity " +
                            (section.sliderFade ?? 1) +
                            "s ease",
                        "pointer-events": "none"
                    });

                $galleryPreview.append($slide);
            });

            //=================================
            // Inserisce la Gallery nel Canvas
            //=================================
            $section.prepend($galleryPreview);

            //=================================
            // Rotazione Gallery
            //=================================
            if(data.images.length > 1){

                let current = 0;

                const interval =
                    Math.max(
                        1,
                        Number(section.sliderInterval ?? 5)
                    ) * 1000;

                setInterval(function(){

                    const $slides =
                        $galleryPreview.children(
                            ".canvas-section-gallery-slide"
                        );

                    if(!$slides.length) return;

                    $slides
                        .eq(current)
                        .css("opacity", 0);

                    current++;

                    if(current >= $slides.length){
                        current = 0;
                    }

                    $slides
                        .eq(current)
                        .css("opacity", 1);

                }, interval);
            }

        })
        .catch(error => {

            console.error(
                "Errore caricamento Gallery nel Canvas:",
                error
            );

        });
}

//=================================
// Preview slider JSON nel Canvas
//=================================
if(
    allowSlider &&
    section.backgroundType === "section" &&
    section.backgroundSection
){

        const folder =
        section.backgroundSection.replace(/^\/+|\/+$/g, "");

    const baseUrl =
        `${window.FB_APP.appUrl}/FB-EDITOR/templates/${folder}/`;

//=================================
// Carica tutti i JSON della cartella
//=================================
const loadSlides = async function(){

    const slides = [];

    try {

        // Recupera l'elenco reale dei file presenti
        const response = await fetch(
            `${window.FB_APP.appUrl}/FB-EDITOR/api/list-section-slides.php?folder=${encodeURIComponent(folder)}`
        );

        if(!response.ok){
            console.error(
                "Errore caricamento elenco slide:",
                response.status
            );
            return slides;
        }

        const data = await response.json();

        if(
            !data.success ||
            !Array.isArray(data.files)
        ){
            console.warn(
                "Nessuna lista slide valida:",
                folder
            );
            return slides;
        }

        //=================================
        // Carica i JSON nell'ordine ricevuto
        //=================================
        for(const file of data.files){

            try {

                const slideResponse =
                    await fetch(baseUrl + file);

                if(!slideResponse.ok){
                    console.warn(
                        "Slide non caricabile:",
                        file
                    );
                    continue;
                }

                const slideData =
                    await slideResponse.json();

                if(
                    slideData &&
                    slideData.section &&
                    typeof slideData.section === "object"
                ){
                    slides.push(slideData.section);
                }

            } catch(error){

                console.error(
                    "Errore caricamento slide:",
                    file,
                    error
                );

                // Non blocca le altre slide
                continue;
            }
        }

    } catch(error){

        console.error(
            "Errore caricamento elenco slide:",
            error
        );
    }

    return slides;
};


    loadSlides().then(function(slides){

        if(!slides.length){
            console.warn(
                "Nessuna slide JSON trovata:",
                baseUrl
            );
            return;
        }

        //=================================
        // Contenitore slider
        //=================================
        const $preview = $("<div>")
            .addClass("canvas-section-json-preview")
            .css({
                position: "absolute",
                inset: 0,
                overflow: "hidden",
                "z-index": 0,
                "pointer-events": "none"
            });


        //=================================
        // Crea le slide
        //=================================
        slides.forEach(function(slide, index){

            const $slide =
                editor.renderSection(
                    slide,
                    false
                );

            $slide
                .removeClass("selected")
                .css({
                    position: "absolute",
                    inset: 0,
                    margin: 0,
                    padding: 0,
                    "pointer-events": "none",
                    "z-index": 0,
                    opacity: index === 0 ? 1 : 0,
                    transition:
                        "opacity " +
                        (section.sliderFade ?? 1) +
                        "s ease"
                });

            $preview.append($slide);
        });


        $section.prepend($preview);


        //=================================
        // Rotazione slide
        //=================================
        if(slides.length > 1){

            let current = 0;

            const interval =
                Math.max(
                    1,
                    Number(section.sliderInterval ?? 5)
                ) * 1000;

            const fade =
                Number(section.sliderFade ?? 1) * 1000;

            setInterval(function(){

                const $slides =
                    $preview.children(".canvas-section");

                if(!$slides.length) return;

                $slides
                    .eq(current)
                    .css("opacity", 0);

                current++;

                if(current >= $slides.length){
                    current = 0;
                }

                $slides
                    .eq(current)
                    .css("opacity", 1);

            }, interval);
        }

    });
}


    return $section;
};
//=================================
//  Create new section
//=================================
 editor.createSection = function(){

    const section = {
        id: editor.utils.uuid("sec"),
        background: "#ffffff",
        padding: "0",
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
// inserisce sezione nella pagina
//=================================
editor.insertSectionTemplate = async function(file){

const res = await fetch(window.APP_URL + "/FB-EDITOR/templates/" + file);    const data = await res.json();

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
  editor.initAllSliders();
  editor.renderPageStructure();
};
