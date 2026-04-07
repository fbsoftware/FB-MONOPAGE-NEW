//===============================================================
// Editor Widgets - proprietà + campi di modifica
//==============================================================    
editor.widgets = {
   header: {   //-----------------------------------------------
        label: "Titolo",
        icon: "📌",

        defaultProps: {
            text: "Titolo ---",
            level: "h2",
            align: "center",    
            color:"#000000"
        },

fields:{
    text:{
        type:"text",
        label:"Titolo"
    },
    level:{
        type:"select",
        label:"Tag",
        options:{
            h1:"H1",
            h2:"H2",
            h3:"H3"
        }
    },
    align:{
        type:"select",
        label:"Allineamento",
        options:{
            left:"Sinistra",
            center:"Centro",
            right:"Destra"   }
    },

    color:{
        type:"select",
        label:"Colore",
        options:{
            "var(--color-primary)":"Primario",
            "var(--color-secondary)":"Secondario",
            "var(--color-accent)":"Accent",
            "var(--color-text)":"Testo",
            "var(--color-bg)":"Sfondo"
        }
    },
},

        render: function(widget){
            const tag = widget.props.level;
            const col = widget.props.color;
            const all = widget.props.align;
            return `
            <div class="widget-header">
                <${tag} style="text-align:${all} ; color:${col}">
                    ${widget.props.text}
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
     margin:0
  },

  fields:{
    text:{
        type:"text",
        label:"Testo"},

    align:{type:"select",    
        options:{left:"Sinistra",
                center:"Centro",
                right:"Destra",
                justify:"Giustificato"}, 
        label:"Allineamento"}, 

    color:{
        type:"color",
        label:"Colore"},
    
    padding:{
        type:"number",
        label:"Padding px"}, 

    margin:{
        type:"number",
        label:"Margin px" }
    } ,

render(widget){
    const p = widget.props || {};

    return `
        <div  class="widget-text" style="
            text-align:${p.align ?? "left"};
            color:${p.color ?? "#000"};
            padding:${p.padding ?? 0}px;
            margin:${p.margin ?? 0}px;
        ">
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
    fontWeight:"400"
},

  fields:{
    text:{type:"textarea", label:"Testo"},
    align:{
        type:"select",
        label:"Allineamento",
        options:{
            left:"Sinistra",
            center:"Centro",
            right:"Destra",
            justify:"Giustificato"
        }
    },
    color:{
        type:"select",
        label:"Colore",
        options:{
            "var(--color-primary)":"Primario",
            "var(--color-secondary)":"Secondario",
            "var(--color-accent)":"Accent",
            "var(--color-text)":"Testo",
            "var(--color-bg)":"Sfondo"
        }
    },
    fontSize:{
        type:"select",
        label:"Dimensione font",
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
        options:{
            "400":"Normale",
            "600":"Semibold",
            "700":"Bold"
        }
    },
    link:{type:"text", label:"Link"},
    image:{type:"text", label:"Immagine URL"}
},

    render(widget){
        const p = widget.props;

        return `<div  class="widget-text" >
        <textarea style="
            text-align:${p.align || "left"};
            color:${p.color || "#000"};
            font-size:${p.fontSize || "16px"};
            font-weight:${p.fontWeight|| "400"};
        ">
            ${p.text || ""}
        </textarea>
        </div>
        `;
    }
    },
    image:  //-----------------------------------------------
       {
    label: "Immagine",
    icon: "🖼️",

    defaultProps: {
        src: "",
        alt: "",
        align: "center",
        width: "150"
    },

    fields: {
        src:{type:"text",label:"Percorso immagine"},
        imageFile:{type:"image_upload",label:"Carica immagine"},
        alt:{type:"text",label:"Alt"},
        align:{
            type:"select",
            label:"Allineamento",
            options:{
                left:"Sinistra",
                center:"Centro",
                right:"Destra"
            }
        },
        width:{type:"number", label:"Larghezza px"}
    },

    render: function(widget){

    let src = widget.props.src || "";

    if(src && !src.startsWith("http") && !src.startsWith("../")){
        src = "../" + src;
    }

    return `
        <div class="widget-image" style="text-align:${widget.props.align};">
            ${src ? `<img src="${src}" alt="${widget.props.alt || ""}" style="width:${widget.props.width}px;">` : ""}
        </div>
    `;
}
    },
    button: {

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
            fontSize: "22px",
            fontWeight: "600"
        },

        fields: {
            text: {
                type: "text",
                label: "Titolo"
            },
            url: {
                type: "text",
                label: "Link"
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
            color: {
                type: "select",
                label: "Colore",
                options: {
                    "var(--color-primary)": "Primario",
                    "var(--color-secondary)": "Secondario",
                    "var(--color-accent)": "Accent",
                    "var(--color-text)": "Testo",
                    "var(--color-bg)": "Sfondo"
                }
            },
            sfondo: {
                type: "select",
                label: "Sfondo",
                options: {
                    "var(--color-primary)": "Primario",
                    "var(--color-secondary)": "Secondario",
                    "var(--color-accent)": "Accent",
                    "var(--color-text)": "Testo",
                    "var(--color-bg)": "Sfondo"
                }
            },
            bordo: {
                type: "number",
                label: "Raggio bordo px"
            },
            padd: {
                type: "number",
                label: "Padding px"
            },
            fontSize: {
                type: "select",
                label: "Dimensione font",
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
                options: {
                    "400": "Normal",
                    "500": "Medium",
                    "600": "SemiBold",
                    "700": "Bold"
                }
            }
        },

        render: function(widget){
            return `
                <div class="widget-button" style="
                    text-align:${widget.props.align}; ">

                    <a href="${widget.props.url}" style="
                        text-decoration:none;
                        font-size:${widget.props.fontSize}px;
                        font-weight:${widget.props.fontWeight};
                        color:${widget.props.color};
                        display:inline-block; 
                        width:auto;
                        padding:${widget.props.padd}px;
                        background-color:${widget.props.sfondo};
                        border-radius:${widget.props.bordo}px; ">
                        ${widget.props.text}
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
                height: "20"
        },

        fields:{
            text:{
                type:"text",
                label:"Testo"},
            height:{
                type:"number",
                label:"Altezza px"}
       },  

        render: function(widget){
            return `
            <div class="widget-spacer" style="height:${widget.props.height}px">
                ${widget.props.text}
            </div>    
            `;
        }

    }  
}

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

    console.log("CREATE WIDGET type =", type);
    console.log("this =", this);
    console.log("this.uid =", this.uid);
    console.log("typeof this.uid =", typeof this.uid);

    const def = this.widgets[type];

    if(!def){
        console.error("Widget type not found:", type);
        return null;
    }

    const newId = this.uid();

    console.log("this.uid() =", newId);
    console.log("typeof this.uid() =", typeof newId);

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
// render pannello dettagli
//==========================================
editor.renderInspector = function(widget, def){

    const $panel = $("#inspector");
    $panel.empty();

    if(!def.fields) return;

    Object.keys(def.fields).forEach(fieldName => {

        const field = def.fields[fieldName];
        const value = widget.props[fieldName] ?? "";
        let input = "";

        if(field.type === "text"){
            input = `
                <input type="text"
                       data-field="${fieldName}"
                       value="${value}">
            `;
        }

        if(field.type === "textarea"){
            input = `
                <textarea data-field="${fieldName}" rows="8">${value}</textarea>
            `;
        }

        if(field.type === "number"){
            input = `
                <input type="number"
                       data-field="${fieldName}"
                       value="${value}">
            `;
        }

        if(field.type === "color"){
            input = `
                <input type="color"
                       data-field="${fieldName}"
                       value="${value}">
            `;
        }

        if(field.type === "select"){
            let options = "";

            Object.keys(field.options).forEach(k => {

                const selected = k === value ? "selected" : "";

                options += `
                    <option value="${k}" ${selected}>
                        ${field.options[k]}
                    </option>
                `;
            });

            input = `
                <select data-field="${fieldName}">
                    ${options}
                </select>
            `;
        }

        if(field.type === "image_upload"){
            input = `
                <input
                    type="file"
                    accept="image/*"
                    data-field="${fieldName}"
                    data-upload-image="1"
                >
            `;
        }

        const row = `
            <div class="inspector-row">
                <label>${field.label}</label>
                ${input}
            </div>
        `;

        $panel.append(row);
    });
};
//=================================
// Render widget
//================================= 
editor.renderWidget = function(widget){

    const def = editor.widgets[widget.type];


console.log("RENDER WIDGET:", widget);


     const selected =
        editor.state.selectedType === "widget" &&
        widget.id === editor.state.selectedId
        ? "selected"
        : ""; 

    const content = def.render(widget);

    return `
        <div class="canvas-widget ${selected}" data-id="${widget.id}">
            <div class="widget-toolbar">
                <button class="widget-delete button">
                    <span class="material-symbols-outlined">delete</span>
                </button>
            </div>

            ${content}
        </div>
    `;
};