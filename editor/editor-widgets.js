//===============================================================
// Editor Widgets - proprietà + campi di modifica
//==============================================================    
editor.widgets = {
   header: {   //------------------------------------------------*
        label: "Titolo",
        icon: "📌",

        defaultProps: {
            text: "Titolo ---",
            level: "h2",
            align: "center",    
            color:"#000000",
            padding:0,
            margin:0  ,
            customCss:"",
            customClass:""
        },

fields:{
    text:{
        type:"text",
        label:"Titolo",
        group:"contenuto"
    },
    level:{
        type:"select",
        label:"Tag",
        group:"contenuto",
        options:{
            h1:"H1",
            h2:"H2",
            h3:"H3"
        }
    },
    align:{
        type:"select",
        label:"Allineamento",
        group:"stile",
        options:{
            left:"Sinistra",
            center:"Centro",
            right:"Destra"   }
    },

    color:{
        type:"color",
        label:"Colore",
        group:"stile",
    },
    padding:{
        type:"number",
        label:"Padding px",
        group:"avanzate"
    },

    margin:{
        type:"number",
        label:"Margin px",
        group:"avanzate"
    },

    customCss: {
        type: "textarea",
        label: "CSS personalizzato",
        group: "avanzate"
},

    customClass: {
        type: "text",
        label: "Classe CSS",
        group: "avanzate"
}
} ,  

        render: function(widget){

    const p = widget.props || {};
    const tag = p.level ?? "h2";

    return `
        <div class="widget-header">
            <${tag} style="
                text-align:${p.align ?? "left"};
                color:${p.color ?? "#000"};
                padding:${p.padding ?? 0}px;
                margin:${p.margin ?? 0}px;
                ${p.customCss ?? ""}"
                class="${p.customClass || ''}">
                ${p.text ?? ""}
            </${tag}>
        </div>
    `;
}

    },
    text: {   //-----------------------------------------------

        label:"Testo",
        icon:"📝",

  defaultProps:{
     text:"Lorem ipsum dolor sit amet. Sit minus quibusdam eum error blanditiis sed suscipit minus. Sed voluptatem eaque non quam quis quo asperiores quisquam qui harum sunt.",
     align:"left",
     color:"#000000",
     padding:0,
     margin:0,
     customCss:"",
     customClass:""
  },

fields:{
    text:{ type:"textarea", label:"Testo", group:"contenuto" },
    align:{ type:"select", label:"Allineamento", group:"stile", 
    options:{left:"Sinistra",
            center:"Centro",
            right:"Destra",
            justify:"Giustificato"} },
    color:{ type:"color", label:"Colore", group:"stile" },
    padding:{ type:"number", label:"Padding px", group:"avanzate" },
    margin:{ type:"number", label:"Margin px", group:"avanzate" },
    customCss: {
        type: "textarea",
        label: "CSS personalizzato",
        group: "avanzate"
        },
    customClass: {
        type: "text",
        label: "Classe CSS",
        group: "avanzate"
        }
},

render(widget){
    const p = widget.props || {};

    return `
        <div  class="widget-text ${p.customClass || ''}" 
                        style="text-align:${p.align ?? "left"};
                        color:${p.color ?? "#000"};
                        padding:${p.padding ?? 0}px;
                        margin:${p.margin ?? 0}px;
                        ${p.customCss ?? ""}">
            ${p.text ?? ""}
        </div>
    `;
}
    },
    textarea: {  //-----------------------------------------------

        label:"Textarea",
        icon:"📝",

defaultProps:{
    text:"Lorem ipsum dolor sit amet. Sit minus quibusdam eum error blanditiis sed",
    align:"left",
    color:"var(--color-text)",
    fontSize:"16px",
    fontWeight:"400",
    customCss:"",
    customClass:""
},

  fields:{
    text:{type:"textarea", label:"Testo", group:"contenuto"},
    align:{
        type:"select",
        label:"Allineamento",
        group:"stile",
        options:{
            left:"Sinistra",
            center:"Centro",
            right:"Destra",
            justify:"Giustificato"
        }
    },
    color:{
        type:"color",
        label:"Colore",
        group:"stile",
    },
    fontSize:{
        type:"select",
        label:"Dimensione font",
        group:"stile",
        options:{
            "16px":"Testo",
            "36px":"Titolo",
            "28px":"Sottotitolo",
            "22px":"Evidenza"
        }
    },

    fontWeight:{
        type:"select",
        label:"Peso font",
        group:"stile",
        options:{
            "400":"Normale",
            "500":"Medio",
            "600":"Semibold",
            "700":"Bold"
        }
    },
    link:{type:"text", label:"Link",group:"avanzate"},
    image:{type:"text", label:"Immagine URL",group:"avanzate"},
        customCss: {
            type: "textarea",
            label: "CSS personalizzato",
            group: "avanzate"
    },
    customClass: {
        type: "text",
        label: "Classe CSS",
        group: "avanzate"
    }
},

    render(widget){
        const p = widget.props;

        return `<div  class="widget-text" >
        <textarea style="
            text-align:${p.align || "left"};
            color:${p.color || "#000"};
            font-size:${p.fontSize || "16px"};
            font-weight:${p.fontWeight|| "400"};
            background:transparent;
            ${p.customCss ?? ""}
        ">
            ${p.text || ""}
        </textarea>
        </div>
        `;
    }
    },
    image: {    //-----------------------------------------------
    label: "Immagine",
    icon: "🖼️",

    defaultProps: {
        src: "",
        alt: "",
        align: "left",
        width: 300,
        customCss:"",
        imgCss:"",
        customClass:""
    },

    fields: {
        src: {
            type: "image_picker",
            label: "Immagine",
            group: "contenuto"
        },

        alt: {
            type: "text",
            label: "Alt",
            group: "contenuto"
        },

        align: {
            type: "select",
            label: "Allineamento",
            group: "stile",
            options: {
                left: "Sinistra",
                center: "Centro",
                right: "Destra"
            }
        },

        width: {
            type: "number",
            label: "Larghezza px",  
            group: "stile"

        },
    
    customCss: {
    type: "textarea",
    label: "CSS div",
    group: "avanzate"
    },

    imgCss: {
    type: "textarea",
    label: "CSS image",
    group: "avanzate"
    },
    customClass: {
        type: "text",
        label: "Classe CSS",
        group: "avanzate"
    }
    },

    render(widget){
        const p = widget.props || {};
        const src = editor.resolveAssetUrl(p.src || "");

        if(!src){
            return `
                <div class="widget-image-empty}"
                     style="text-align:center; margin-top:10px;">
                    <img src="https://dummyimage.com/200x200/cacaca/fff.png&text=dummy-image"
                </div>
            `;
        }

        return `
            <div class="widget-image" 
                style="text-align:${p.align ?? "left"};
                ${p.customCss ?? ""}">
                    <img src="${src}" alt="${p.alt ?? ""}" 
                    style="width:${p.width ?? 300}px;
                    ${p.imgCss ?? ""}" class="${p.customClass || ''}">
            </div>
        `;
    }
    },
    button: {   // -----------------------------------------------

        label: "Bottone",
        icon: "🔘",

        defaultProps: {
            text: "CERCA",
            url: "#",
            align: "center",
            color: "#000000",
            sfondo: "#ffa500",
            bordo: 25,
            padd: 20,
            padding: 20,
            fontSize: "22px",
            fontWeight: "600",
            customCss:"",
            buttonCss:"",
            customClass:""
        },

        fields: {
            text: {
                type: "text",
                label: "Titolo",
                group: "contenuto"

            },
            url: {
                type: "text",
                label: "Link",
                group: "contenuto"
            },
            align: {
                type: "select",
                label: "Allineamento",
                group: "stile",
                options: {
                    left: "Sinistra",
                    center: "Centro",
                    right: "Destra"
                }
            },
            color: {
                type: "color",
                label: "Colore",
                group: "stile",
            },
            sfondo: {
                type: "color",
                label: "Sfondo",
                group: "stile",
            },
            bordo: {
                type: "number",
                label: "Raggio bordo px",
                group: "avanzate"

            },
            padding: {
                type: "number",
                label: "Padding div px",
                group: "avanzate"
            },
           padd: {
                type: "number",
                label: "Padding button px",
                group: "avanzate"
            },
            fontSize: {
                type: "select",
                label: "Dimensione font",
                group: "avanzate",
                options: {
                    "16": "Testo",
                    "36": "Titolo",
                    "28": "Sottotitolo",
                    "22": "Evidenza"
                }
            },
            fontWeight: {
                type: "select",
                label: "Peso font",
                group: "avanzate",
                options: {
                    "400": "Normal",
                    "500": "Medium",
                    "600": "SemiBold",
                    "700": "Bold"
                }
            },

        customCss: {
        type: "textarea",
        label: "CSS div",
        group: "avanzate"
        },
        
        buttonCss: {
        type: "textarea",
        label: "CSS button",
        group: "avanzate"
        },
        customClass: {
            type: "text",
            label: "Classe CSS",
            group: "avanzate"
        }
        },

        render: function(widget){
            const p = widget.props || {};

            return `
                <div class="widget-button ${p.customClass || ''}" 
                    style="text-align:${p.align}; 
                    padding:${p.padding}px;
                    ${p.customCss ?? ""}">

                    <a href="${p.url}" style="
                        text-decoration:none;
                        font-size:${p.fontSize}px;
                        font-weight:${p.fontWeight};
                        color:${p.color};
                        display:inline-block; 
                        width:auto;
                        padding:${p.padd}px;
                        background-color:${p.sfondo};
                        border-radius:${p.bordo}px; 
                        ${p.buttonCss ?? ""}">
                        ${p.text}
                    </a>
                    
                </div>
            `;
        }
    },
    spacer: {   //-----------------------------------------------

        label: "Spaziatore",
        icon: "🔘",

        defaultProps: {
                text: "",
                height: "20",
                customCss:"",
                customClass:""
        },

        fields:{
            height:{
                type:"number",
                label:"Altezza px",
                group:"stile" },
            customCss: {
                type: "textarea",
                label: "CSS div",
                group: "avanzate"
                },
                customClass: {  
                    type: "text",
                    label: "Classe CSS",
                    group: "avanzate"
                }

       },  

        render: function(widget){
            const p = widget.props || {};
            return `
            <div class="widget-spacer ${p.customClass || ''}" 
            style="height:${p.height}px;
            ${p.customCss ?? ""}">
            </div>    
            `;
        }

    }  ,
    icon: {     //-----------------------------------------------
    label: "Icona",
    icon: "⭐",

    defaultProps: {
        name: "home",
        size: 48,
        color: "var(--color-primary)",
        align: "center",
        padding: 0,
        margin: 0,
        customCss:"",
        iconCss:"",
        customClass:""
    },

    fields: {
        name: {
            type: "text",
            label: "Nome icona",
            group: "contenuto"
        },

        size: {
            type: "number",
            label: "Dimensione px",
            group: "stile"  
        },

        color: {
            type: "color",
            label: "Colore",
            group: "stile"
        },

        align: {
            type: "select",
            label: "Allineamento",
            options: {
                left: "Sinistra",
                center: "Centro",
                right: "Destra"
            }
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
            label: "CSS div",
            group: "avanzate"
            },
            
        iconCss: {
            type: "textarea",
            label: "CSS icona",
            group: "avanzate"
            },

        customClass: {
            type: "text",
            label: "Classe CSS",
            group: "avanzate"
        }
    },

    render(widget) {
        const p = widget.props || {};

        return `
            <div class="widget-icon" style="
                text-align:${p.align ?? "center"};
                padding:${p.padding ?? 0}px;
                margin:${p.margin ?? 0}px;
                ${p.customCss ?? ""} ">

                <span class="material-symbols-outlined ${p.customClass || ''}" style="
                    font-size:${p.size ?? 48}px!important;
                    color:${p.color ?? "var(--color-primary)"};
                    ${p.iconCss ?? ""}">
                    ${p.name ?? "home"}
                </span>
            </div>
        `;
    }
    },
    video: {    //-----------------------------------------------
    label: "Video",
    icon: "🎥",

    defaultProps: {
        url: "",
        aspectRatio: "16:9",
        align: "center",
        padding: 20,
        margin: 20,
        customCss:"",
        videoCss:"",
        customClass:""

    },

    fields: {
        url: {
            type: "text",
            label: "URL YouTube",
            group: "contenuto"
        },

        aspectRatio: {
            type: "select",
            label: "Formato",
            group: "stile",
            options: {
                "16:9": "16:9",
                "4:3": "4:3",
                "1:1": "1:1"
            }
        },

        align: {
            type: "select",
            label: "Allineamento",
                group: "stile",
            options: {
                left: "Sinistra",
                center: "Centro",
                right: "Destra"
            }
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
            label: "CSS div",
            group: "avanzate"
            },

            videoCss: {
            type: "textarea",
            label: "CSS video",
            group: "avanzate"
            },
        customClass: {
            type: "text",
            label: "Classe CSS",
            group: "avanzate"
    } 
    },

    render(widget) {
        const p = widget.props || {};
        const videoId = editor.getYoutubeVideoId(p.url || "");

        if (!videoId) {
            return `
                <div class="widget-video" 
                    style="text-align:${p.align ?? "center"};
                    padding:${p.padding ?? 0}px;
                    margin:${p.margin ?? 0}px;
                ">
                    <div style="
                        padding:20px;
                        border:1px dashed #ccc;
                        border-radius:8px;   ">
                        Inserisci un URL YouTube valido
                    </div>
                </div>
            `;
        }

        const ratio = editor.getAspectRatioPadding(p.aspectRatio || "16:9");

        return `
            <div class="widget-video ${p.customClass || ''} " style="
                text-align:${p.align ?? "center"};
                padding:${p.padding ?? 0}px;
                margin:${p.margin ?? 0}px;
                ${p.customCss ?? ""}
            ">
                <div style="
                    position:relative;
                    width:100%;
                    max-width:100%;
                    padding-top:${ratio};
                    overflow:hidden;
                    border-radius:8px;
                    ${p.videoCss ?? ""} 
                ">
                    <iframe
                        src="https://www.youtube.com/embed/${videoId}"
                        title="YouTube video player"
                        style="
                            position:absolute;
                            top:0;
                            left:0;
                            width:100%;
                            height:100%;
                            border:0;
                        "
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>
        `;
    }
    },
    divider: {   //-----------------------------------------------

        label: "Divisore",
        icon: "🧱",

        defaultProps: {
        name: "home",
        size: 48,
        color: "var(--color-primary)",
        padding: 0,
        margin: 0,
        height: 1,
        lineaCss:"",
        iconaCss:"",
        customClass:""
        },

        fields:{
            name:{
                type:"text",
                label: "Nome icona",
                group: "contenuto"
            },
            size:{
                type:"number",
                label:"Altezza px",
                group: "stile"
            },
            color: {
                type: "color",
                label: "Colore",
                group: "stile"
        },
            height: {
                type: "number",
                label: "Altezza riga px",
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
        lineaCss: {
            type: "textarea",
            label: "CSS linea",
            group: "avanzate"
            },

        iconaCss: {
            type: "textarea",
            label: "CSS icona",
            group: "avanzate"
            } ,
        customCss: {
            type: "textarea",
            label: "CSS div",
            group: "avanzate"      
 }, 
        render: function(widget){
            const p = widget.props || {};
            return `
            <div class="widget-divider ${p.customClass || ''}" 
                    style="display: flex; 
                    flex-direction: row;
                    align-items: center; 
                    gap: 15px; 
                    margin: 20px 0;
                    ${p.lineaCss ?? ""} ">
                <div style="flex: 1; height: ${p.height ?? 1}px; 
                    background: ${resolveColor(p.color)};
                    ${p.lineaCss ?? ""} ">
                </div>
                    <div><span class="material-symbols-outlined" style="
                    font-size:${p.size ?? 48}px!important;
                    color:${p.color ?? "var(--color-primary)"};
                    ${p.iconCssaCss ?? ""} ">
                    ${p.name ?? "home"}
                    </span></div>
                <div style="flex: 1; height: ${p.height ?? 1}px; 
                        background: ${resolveColor(p.color)};
                        ${p.lineaCss ?? ""} ">
                </div>
            </div>    
            `;
        }

    }  
    },
    richtext: {  //-----------------------------------------------
    label: "Richtext",
    icon: "📄",

    defaultProps: {
        html: "<p>Scrivi qui il testo...</p>",
        padding: 0,
        margin: 0,
        customCss: ""
    },

    fields: {
        html: {
            type: "richtext",
            label: "Testo",
            group: "contenuto"
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
    },

    render(widget){
        const p = widget.props || {};

        return `
            <div class="widget-richtext" style="
                padding:${p.padding ?? 0}px;
                margin:${p.margin ?? 0}px;
                ${p.customCss ?? ""}
            ">
                ${p.html ?? ""}
            </div>
        `;
    }
    },
    navbar: {  //-----------------------------------------------
    label: "Navbar",
    icon: "📚",

    defaultProps: {
        align: "right",
        gap: 20,
        color: "#000000",
        fontSize: 16,
        padding: 10,
        customCss:"",
            items: [
        { label: "Home", type: "anchor", target: "home" },
        { label: "Promo", type: "anchor", target: "promo" },
        { label: "Portfolio", type: "anchor", target: "portfolio" },
        { label: "CTA", type: "anchor", target: "CTA" }
        ]
    },

    fields: {
        align: {
            type: "select",
            label: "Allineamento",
            options: {
                left: "Sinistra",
                center: "Centro",
                right: "Destra"
            },
            group: "stile"
        },

        gap: {
            type: "number",
            label: "Spazio voci",
            group: "stile"
        },

        color: {
            type: "color",
            label: "Colore link",
            group: "stile"
        },

        fontSize: {
            type: "number",
            label: "Dimensione testo",
            group: "stile"
        },

        padding: {
            type: "number",
            label: "Padding",
            group: "stile"
        },
        customCss: {
            type: "textarea",
            label: "CSS personalizzato",
            group: "avanzate"
        },
        items: {
            type: "textarea",
            label: "Voci menu JSON",
            group: "contenuto"
        }
    },

    render(widget) {
        const p = widget.props || {};

        const align = p.align ?? "right";
        const gap = parseInt(p.gap ?? 20, 10);
        const color = p.color ?? "#000000";
        const fontSize = parseInt(p.fontSize ?? 16, 10);
        const padding = parseInt(p.padding ?? 10, 10);

        let justify = "flex-end";

        if (align === "left") justify = "flex-start";
        if (align === "center") justify = "center";

        const items = p.items || [];
        const links = items.map(item => {
        const label = item.label || "Voce";
        const type = item.type || "anchor";
        const target = item.target || "";

    let href = "#";

    if (type === "anchor") {
        href = "#" + target.replace(/^#/, "");
    }

    if (type === "page") {
        href = "pages/" + target.replace(/\.html$/, "") + ".html";
    }

    if (type === "url") {
        href = target;
    }
  return `
        <a href="${href}" style="color:${color};">
            ${label}
        </a>
    `;
}).join("");

return `
    <nav class="widget-navbar" style="
        display:flex;
        justify-content:${justify};
        align-items:center;
        gap:${gap}px;
        padding:${padding}px;
        font-size:${fontSize}px;
    ">
        ${links}
    </nav>
`;
    }
},
};
//=================================
// Apre pannello dettagli widget 
//=================================
editor.openWidgetInspector = function(id){

    const widget = editor.findWidgetById(id);
    if (!widget) return;

    const def = editor.widgets[widget.type];

    editor.renderInspector(widget, def);
};

//=================================
// editor widget uid
//=================================
editor.uid = (function(){
    let counter = 0;
    return function(){
        counter++;
        return "w" + Date.now() + "_" + counter;
    };
})();

//=================================
// Render widget palette
//=================================
editor.renderWidgetPalette = function(){

            const $panel = $("#widgets-panel");
            $panel.empty();


    Object.keys(editor.widgets).forEach(function(type){

        const widget = editor.widgets[type];

        const $item = $("<div>")
            .addClass("palette-widget")
            .attr("draggable", true)
            .attr("data-widget", type)
            .text(widget.icon + " " + widget.label);

        $panel.append($item);

    });

};
 
//=================================
//  3️⃣ Trova il widget selezionato
//=================================
editor.getSelectedWidget = function(){

    const id = editor.state.selected.id;

    for(const section of editor.state.sections){
        for(const column of section.columns){
            for(const widget of column.widgets){
                if(widget.id === id) return widget;
            }
        }
    }
    return null;
};

//=================================
// Crea widget nel canvas
//=================================
editor.createWidget = function(type){

    const def = this.widgets[type];

    if(!def){
        console.error("Widget type not found:", type);
        return null;
    }

    const newId = this.uid();

    return {
        id: newId,
        type: type,
        props: structuredClone(def.defaultProps)
    };
};

//==================================================
// cerca colonna per dettagli
//==================================================
editor.findColumnById = function(id){
    for (const section of editor.state.sections){
        for (const col of section.columns){
            if (col.id == id) return col;
        }
    }
};

//==================================================
// converte formato per colori
//==================================================
function resolveColor(value) {
  if (!value) return "#000000";

  const match = value.match(/var\(--(.+?)\)/);
  if (match) {
    return getComputedStyle(document.documentElement)
      .getPropertyValue(`--${match[1]}`)
      .trim();
  }

  return value;
}

//==========================================
// render pannello dettagli widget
//==========================================
editor.renderInspector = function(widget, def){

    const $panel = $("#inspector");
    $panel.empty();

    if(!widget || !def){
        $panel.html("<p>Widget non trovato.</p>");
        return;
    }

$panel.append(`
    <div class="inspector-header">
       <h3 style="text-align:center; margin:10px;">
           ${def.label || "Widget"}
       </h3>
    </div>
`);

    widget.props = widget.props || {};

    Object.keys(def.defaultProps || {}).forEach(key => {
        if(widget.props[key] === undefined){
            widget.props[key] = def.defaultProps[key];
        }
    });

    if(!def.fields) return;

    const groupOrder = editor.inspectorGroupOrder || [
        "contenuto",
        "stile",
        "immagini",
        "overlay",
        "avanzate"
    ];

    const groups = {};

    groupOrder.forEach(groupName => {
        groups[groupName] = [];
    });

    Object.keys(def.fields).forEach(fieldName => {
        const field = def.fields[fieldName];
        const group = field.group || "contenuto";

        if(!groups[group]){
            groups[group] = [];
        }

        groups[group].push(fieldName);
    });

    groupOrder.forEach(groupName => {

        if(!groups[groupName] || !groups[groupName].length) return;

        const isOpen = editor.state.openInspectorGroup === groupName;
        const openClass = isOpen ? "open" : "";
        const openStyle = isOpen ? "" : 'style="display:none;"';

 let       html = `
            <div class="inspector-accordion ${openClass}">
                <div class="accordion-title" data-group="${groupName}">
                    ${editor.inspectorGroups?.[groupName] || groupName}
                </div>
                <div class="accordion-content" ${openStyle}>
        `;
groups[groupName].forEach(fieldName => {

    const field = def.fields[fieldName];

    let value =
        widget.props && widget.props[fieldName] !== undefined
            ? widget.props[fieldName]
            : (def.defaultProps?.[fieldName] ?? "");

    if (fieldName === "items" && Array.isArray(value)) {
        value = JSON.stringify(value, null, 2);
    }

    const input = editor.renderInspectorInput(fieldName, field, value);

    html += `
        <div class="inspector-row">
            <label>${field.label}</label>
            ${input}
        </div>
    `;
});
        html += `
                </div>
            </div>
        `;

        $panel.append(html);
    });
};


//==================================
// inspector generico per elementi
//==================================
editor.renderElementInspector = function(title, target, fields, dataAttr){

    const $panel = $("#inspector");
    $panel.empty();

    if(!target){
        $panel.html("<p>Elemento non trovato.</p>");
        return;
    }

    if(!fields){
        console.error("Fields mancanti per inspector:", title, target);
        $panel.html("<p>Campi inspector mancanti.</p>");
        return;
    }

    $panel.append(`
    <div class="inspector-header">
        ${title}
    </div>
`);

    const groupOrder = editor.inspectorGroupOrder || [
        "contenuto",
        "stile",
        "immagini",
        "overlay",
        "avanzate"
    ];

    const groups = {};

    groupOrder.forEach(groupName => {
        groups[groupName] = [];
    });

    Object.keys(fields).forEach(fieldName => {
        const field = fields[fieldName];
        const group = field.group || "contenuto";

        if(!groups[group]){
            groups[group] = [];
        }

        groups[group].push(fieldName);
    });

    groupOrder.forEach(groupName => {

        if(!groups[groupName] || !groups[groupName].length) return;

        const isOpen = editor.state.openInspectorGroup === groupName;
        const openClass = isOpen ? "open" : "";
        const openStyle = isOpen ? "" : 'style="display:none;"';

        let html = `
            <div class="inspector-accordion ${openClass}">
                <div class="accordion-title" data-group="${groupName}">
                    ${editor.inspectorGroups?.[groupName] || groupName}
                </div>
                <div class="accordion-content" ${openStyle}>
        `;

        groups[groupName].forEach(fieldName => {

            const field = fields[fieldName];
            const value = target[fieldName] ?? "";

            let input = editor.renderInspectorInput(fieldName, field, value, dataAttr);

            if(fieldName === "backgroundImage" && value){
                input += `
                    <button type="button"
                            class="clear-section-bg-image"
                            style="margin-top:8px;">
                        Rimuovi immagine
                    </button>
                `;
            }

            html += `
                <div class="inspector-row">
                    <label>${field.label}</label>
                    ${input}
                </div>
            `;
        });

        html += `
                </div>
            </div>
        `;

        $panel.append(html);
    });
};
//================================= 
// render input campo dettagli
//=================================
editor.renderInspectorInput = function(fieldName, field, value, dataAttr){

    let input = "";

    const attr = dataAttr
        ? `data-${dataAttr}-field="${fieldName}"`
        : `data-field="${fieldName}"`;

    if(field.type === "text"){
        input = `
            <input type="text"
                   ${attr}
                   value="${value}">
        `;
    }

    if(field.type === "textarea"){
        input = `
            <textarea ${attr} rows="8">${value}</textarea>
        `;
    }

    if(field.type === "number"){
        input = `
            <input type="number"
                   ${attr}
                   value="${value}">
        `;
    }

    if(field.type === "range"){
        input = `
            <input type="range"
                   min="${field.min ?? 0}"
                   max="${field.max ?? 100}"
                   step="${field.step ?? 1}"
                   ${attr}
                   value="${value}">
        `;
    }

if(field.type === "color"){
    input = `
        <select ${attr} class="color-select">
            <option value="">Custom / nessuno</option>
            <option value="var(--color-primary)" ${value === "var(--color-primary)" ? "selected" : ""}>Primario</option>
            <option value="var(--color-secondary)" ${value === "var(--color-secondary)" ? "selected" : ""}>Secondario</option>
            <option value="var(--color-accent)" ${value === "var(--color-accent)" ? "selected" : ""}>Accent</option>
            <option value="var(--color-text)" ${value === "var(--color-text)" ? "selected" : ""}>Testo</option>
            <option value="var(--color-bg)" ${value === "var(--color-bg)" ? "selected" : ""}>Sfondo</option>
        </select>

        <input type="color"
               ${attr}
               class="color-picker"
               value="${value && value.startsWith("#") ? value : "#000000"}">
    `;
}

    if(field.type === "select"){
        let options = "";

        Object.keys(field.options || {}).forEach(k => {
            const selected = k === value ? "selected" : "";

            options += `
                <option value="${k}" ${selected}>
                    ${field.options[k]}
                </option>
            `;
        });

        input = `
            <select ${attr}>
                ${options}
            </select>
        `;
    }

    if(field.type === "image_picker"){
        input = `
            <div class="image-picker-slot"
                 data-image-picker="${fieldName}">
                Loading...
            </div>
        `;

        setTimeout(async () => {
            const html = await editor.renderImagePicker(fieldName, value);
            $("#inspector")
                .find(`[data-image-picker="${fieldName}"]`)
                .html(html);
        }, 0);
    }
    if(field.type === "richtext"){
    input = `
        <div class="richtext-toolbar">
            <button type="button" data-rich-cmd="bold">B</button>
            <button type="button" data-rich-cmd="italic">I</button>
            <button type="button" data-rich-cmd="insertUnorderedList">• Lista</button>
            <button type="button" data-rich-format="p">P</button>
            <button type="button" data-rich-format="h2">H2</button>
            <button type="button" data-rich-link="1">Link</button>
            <button type="button" data-rich-image="1">Img</button>
        </div>

        <div class="richtext-editor"
             contenteditable="true"
             data-field="${fieldName}">
            ${value}
        </div>
    `;
}

    if(!input){
        input = `
            <div class="inspector-error">
                Tipo campo non supportato: ${field.type}
            </div>
        `;
    }

    return input;
};
//=================================
// Render widget
//================================= 
editor.renderWidget = function(widget){
    const def = editor.widgets[widget.type];

     const selected =
        editor.state.selectedType === "widget" &&
        widget.id === editor.state.selectedId
        ? "selected"
        : ""; 

    const content = def.render(widget);

    return `
        <div class="canvas-widget ${selected}" data-id="${widget.id}">
            <div class="widget-toolbar">
                <button class="duplicate-widget button" title="Duplica widget">
                    <span class="material-symbols-outlined">content_copy</span>
                </button>
                <button class="widget-delete button">
                    <span class="material-symbols-outlined">delete</span>
                </button>
            </div>

            ${content}
        </div>
    `;
};

//=================================
//  URL YouTube -> video ID
//=================================
editor.getYoutubeVideoId = function(url) {
    if (!url) return "";

    const patterns = [
        /youtube\.com\/watch\?v=([^&]+)/,
        /youtu\.be\/([^?&]+)/,
        /youtube\.com\/embed\/([^?&]+)/,
        /youtube\.com\/shorts\/([^?&]+)/
    ];

    for (const pattern of patterns) {
        const match = url.match(pattern);
        if (match && match[1]) {
// FB: render colonna completo
//console.log('URL=',match[0], 'ID=', match[1]);

            return match[1];
        }
    }

    return "";
};

//=================================
//  aspett ratio -> padding
//=================================
editor.getAspectRatioPadding = function(ratio) {
    switch (ratio) {
        case "4:3":
            return "75%";
        case "1:1":
            return "100%";
        case "16:9":
        default:
            return "56.25%";
    }
};

//=================================
//  image picker
//=================================
editor.renderImagePicker = async function(fieldName, value){

    const images = await editor.loadImages();
    let html = `<div class="image_picker">`;

    images.forEach(img => {

        const selected = img.file === value ? "selected" : "";

        html += `
            <div class="image-thumb ${selected}"
                 data-field="${fieldName}"
                 data-value="${img.file}"
                 draggable="false">
                 
                <img src="${editor.resolveAssetUrl(img.file)}"
                     alt="${img.name}"
                     draggable="false">

                <div class="img-name">${img.name}</div>
            </div>
        `;
    });

    html += `</div>`;

    return html;
};

//=================================
//  inspector groups
//=================================
editor.inspectorGroups = {
    contenuto: "Contenuto",
    stile:     "Stile",
    avanzate:  "Avanzate"
};

//=================================
//  render campo colore
//=================================
editor.renderInspectorColorField = function(fieldName, value){
    const pickerValue =
        typeof value === "string" && value.startsWith("#")
            ? value
            : "#000000";

    return `
        <select data-field="${fieldName}">
            <option value="var(--color-primary)" ${value === "var(--color-primary)" ? "selected" : ""}>Primario</option>
            <option value="var(--color-secondary)" ${value === "var(--color-secondary)" ? "selected" : ""}>Secondario</option>
            <option value="var(--color-accent)" ${value === "var(--color-accent)" ? "selected" : ""}>Accent</option>
            <option value="var(--color-text)" ${value === "var(--color-text)" ? "selected" : ""}>Testo</option>
            <option value="var(--color-bg)" ${value === "var(--color-bg)" ? "selected" : ""}>Sfondo</option>
            <option value="transparent" ${value === "transparent" ? "selected" : ""}>Trasparente</option>
        </select>

        <input
            type="color"
            data-field="${fieldName}"
            value="${pickerValue}">
    `;
};

//=================================
//  clona widget
//=================================
editor.cloneWidget = function(widget){

    const clone = JSON.parse(JSON.stringify(widget));

    clone.id = editor.uid();

    return clone;
};

//=================================
//  comandi testo avanzato
//=================================
$(document).on("click", "[data-rich-cmd]", function(e){
    e.preventDefault();

    const cmd = $(this).data("rich-cmd");
    document.execCommand(cmd, false, null);
});
// formati testo avanzato
$(document).on("click", "[data-rich-format]", function(e){
    e.preventDefault();

    const tag = $(this).data("rich-format");
    document.execCommand("formatBlock", false, tag);
});
// link
$(document).on("click", "[data-rich-link]", function(e){
    e.preventDefault();

    const url = prompt("URL link:");
    if(!url) return;

    document.execCommand("createLink", false, url);
});
$(document).on("click", "[data-rich-image]", function(e){
    e.preventDefault();

    let url = prompt("URL o nome file immagine:");
    if(!url) return;

    url = url.trim();

    if(
        !url.startsWith("http://") &&
        !url.startsWith("https://") &&
        !url.startsWith("/")
    ){
        url = "/FB-JSON/assets/images/" + url;
    }

    const width = prompt("Larghezza immagine, es. 300px o 50%:", "300px");
    const align = prompt("Allineamento: left, center, right", "center");

    let style = `
        width:${width};
        max-width:100%;
        height:auto;
    `;

    if(align === "center"){
        style += `
            display:block;
            margin:10px auto;
        `;
    }

    if(align === "left"){
        style += `
            display:block;
            margin:10px 20px 10px 0;
            float:left;
        `;
    }

    if(align === "right"){
        style += `
            display:block;
            margin:10px 0 10px 20px;
            float:right;
        `;
    }

    const html = `
        <img src="${url}" style="${style}">
    `;

    document.execCommand("insertHTML", false, html);
});
//=================================
//  aggiorna props testo avanzato
//=================================
$(document).on("input blur", ".richtext-editor", function(e){

    const field = $(this).data("field");
    const widget = editor.findWidgetById(editor.state.selectedId);

    if(!widget) return;

    widget.props[field] = $(this).html();
    editor.state.isDirty = true;
});