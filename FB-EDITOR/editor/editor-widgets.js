//===============================================================
// Editor Widgets - proprietà + campi di modifica
//============================================================== 
var editor = editor || {};

editor.widgets = editor.widgets || {};

// ==================================================
// WIDGET RICHTEXT
// ==================================================
editor.widgets.richtext = {

    label: "Richtext",
    icon: "📄",

    defaultProps: {
        html: "<p>Scrivi qui il testo...</p>",
        padding: 0,
        margin: 0,
        customCss: "",
        customClass:""
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
            <div class="widget-richtext ${p.customClass}" style="
                padding:${p.padding ?? 0}px;
                margin:${p.margin ?? 0}px;
                ${p.customCss ?? ""}
            ">
                ${p.html ?? ""}
            </div>
        `;
    }
};
// ==================================================
// WIDGET NAVBAR
// ==================================================
editor.widgets.navbar = {

    label: "Navbar",
    icon: "📚",

    defaultProps: {
        align: "right",
        gap: 20,
        color: "#000000",
        fontSize: 16,
        padding: 10,
        customCss: "",
        customClass: "",

        items: [
            {
                label: "Home",
                type: "anchor",
                target: "home"
            },
            {
                label: "Promo",
                type: "anchor",
                target: "promo"
            },
            {
                label: "Portfolio",
                type: "anchor",
                target: "portfolio"
            },
            {
                label: "CTA",
                type: "anchor",
                target: "CTA"
            }
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
            type: "menuItems",
            label: "Voci menu",
            group: "contenuto"
        },
        customClass: {
            type: "text",
            label: "Classe CSS",
            group: "avanzate"
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

        if (align === "left") {
            justify = "flex-start";
        }

        if (align === "center") {
            justify = "center";
        }

        const items = Array.isArray(p.items)
            ? p.items
            : [];

        const links = items
            .map(item => {

                const label = item.label || "Voce";
                const type = item.type || "anchor";
                const target = item.target || "";

                let href = "#";

                if (type === "anchor") {
                    href = "#" + target.replace(/^#/, "");
                }

                if (type === "page") {
                    href =
                        "pages/" +
                        target.replace(/\.html$/, "") +
                        ".html";
                }

                if (type === "url") {
                    href = target;
                }

                return `
                    <a
                        href="${href}"
                        style="color:${color};"
                    >
                        ${label}
                    </a>
                `;
            })
            .join("");

        return `
            <nav class="widget-navbar ${p.customClass}" style="
                display:flex;
                justify-content:${justify};
                align-items:center;
                gap:${gap}px;
                padding:${padding}px;
                font-size:${fontSize}px;
                ${p.customCss ?? ""}
            ">
                ${links}
            </nav>
        `;
    }
};
// ==================================================
// WIDGET IMAGESLIDE
// ==================================================
editor.widgets.imageSlide = {

    label: "Slider",
    icon: "🎞️",

    defaultProps: {
        folder: "portfolio",
        height: 400,
        interval: 5000,
        showArrows: true,
        showDots: true,
        customCss: "",
        customClass: ""
    },

    fields: {

        folder: {
            type: "galleryFolderSelect",
            label: "Cartella immagini",
            group: "contenuto"
        },

        height: {
            type: "number",
            label: "Altezza slider px",
            group: "stile"
        },

        interval: {
            type: "number",
            label: "Intervallo autoplay ms",
            group: "stile"
        },

        showArrows: {
            type: "checkbox",
            label: "Mostra frecce",
            group: "stile"
        },

        showDots: {
            type: "checkbox",
            label: "Mostra indicatori",
            group: "stile"
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
    },

    render(widget) {

        const p = widget.props || {};

        const folder = p.folder || "portfolio";
        const height = parseInt(p.height ?? 400, 10);
        const interval = parseInt(p.interval ?? 5000, 10);

        const showArrows = editor.toBoolean(
            p.showArrows,
            true
        );

        const showDots = editor.toBoolean(
            p.showDots,
            true
        );

        const images = editor.getGalleryImages(folder);

        if (!images.length) {

            /*
             * Usa la funzione già funzionante della Gallery.
             * Non creiamo una seconda cache.
             */
            editor.loadGalleryImages(folder);

            return `
                <div class="widget-slider-empty">
                    Caricamento slider: ${folder}...
                </div>
            `;
        }

        const slides = images.map((src, index) => `
            <div class="slide ${index === 0 ? "active" : ""}  style=">
                <img
                    src="${src}"
                    alt=""
                    style=" 
                        width:100%;
                        height:${height}px;
                        display:block;
                        object-fit:cover;
                                            "
                >
            </div>
        `).join("");

        const arrows = showArrows
            ? `
                <button type="button" class="prev">❮</button>
                <button type="button" class="next">❯</button>
            `
            : "";

        const dots = showDots
            ? `
                <div class="slider-dots">
                    ${images.map((src, index) => `
                        <button
                            type="button"
                            class="dot ${index === 0 ? "active" : ""}"
                            data-slide="${index}"
                            aria-label="Immagine ${index + 1}">
                        </button>
                    `).join("")}
                </div>
            `
            : "";

        return `
            <div
                class="widget-slider  ${p.customClass || ''}"
                data-widget-id="${widget.id}"
                data-interval="${interval}"
                style = "${p.customCss ?? ""}";
            >
                <div class="slider-slides">
                    ${slides}
                </div>

                ${arrows}
                ${dots}
            </div>
        `;
    }
    };
// ==================================================
// WIDGET HEADER
// ==================================================
editor.widgets.header = {
 
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
        <div class="widget-header ${p.customClass || ''}">
            <${tag} style="
                text-align:${p.align ?? "left"};
                color:${p.color ?? "#000"};
                padding:${p.padding ?? 0}px;
                margin:${p.margin ?? 0}px;
                ${p.customCss ?? ""}">
                ${p.text ?? ""}
            </${tag}>
        </div>
    `;
}
};
// ==================================================
// WIDGET TEXT
// ==================================================
editor.widgets.text = {
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
};
// ==================================================
// WIDGET TEXTAREA
// ==================================================
editor.widgets.textarea = {
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
    };
// ==================================================
// WIDGET IMAGE
// ==================================================
editor.widgets.image = {
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
            <div class="widget-image ${p.customClass || ''}" 
                style="text-align:${p.align ?? "left"};
                ${p.customCss ?? ""}">
                    <img src="${src}" alt="${p.alt ?? ""}" 
                    style="width:${p.width ?? 300}px;
                    ${p.imgCss ?? ""}">
            </div>
        `;
    }
};
//==================================================
// WIDGET: HERO SLIDE
//==================================================
editor.widgets.heroSlide = {

    label: "Hero Slider",
    icon: "🖼️",

    defaultProps: {
        folder: "portfolio",
        height: 500,
        interval: 5000,
        showArrows: true,
        showDots: true,

        slides: [],

        customClass: "",
        customCss: ""
    },

    fields: {

        folder: {
            type: "galleryFolderSelect",
            label: "Cartella immagini",
            group: "contenuto"
        },

        slides: {
            type: "heroSlides",
            label: "Contenuti slide",
            group: "contenuto"
        },

        height: {
            type: "number",
            label: "Altezza slider px",
            group: "stile"
        },

        interval: {
            type: "number",
            label: "Intervallo autoplay ms",
            group: "stile"
        },

        showArrows: {
            type: "checkbox",
            label: "Mostra frecce",
            group: "stile"
        },

        showDots: {
            type: "checkbox",
            label: "Mostra indicatori",
            group: "stile"
        },

        customClass: {
            type: "text",
            label: "Classe CSS",
            group: "avanzate"
        },

        customCss: {
            type: "textarea",
            label: "CSS personalizzato",
            group: "avanzate"
        }
    },

render(widget) {

    const p = widget.props || {};

    const height = parseInt(p.height ?? 500, 10);

    const showArrows = editor.toBoolean(
        p.showArrows,
        true
    );

    const showDots = editor.toBoolean(
        p.showDots,
        true
    );

    const slides = Array.isArray(p.slides)
        ? p.slides
        : [];

    if (!slides.length) {
        return `
            <div class="widget-hero-slide-empty">
                Nessuna slide disponibile
            </div>
        `;
    }

    const slidesHtml = slides.map((slide, index) => {

        const image = slide.image || "";
        const title = slide.title || "";
        const text = slide.text || "";

        return `
            <div class="slide hero-slide ${index === 0 ? "active" : ""}">

                <img
                    src="${image}"
                    alt=""
                    style="
                        width:100%;
                        height:${height}px;
                        object-fit:cover;
                        display:block;
                    "
                >

                <div class="hero-slide-overlay">
                    <div class="hero-slide-content">

                        ${title
                            ? `<h2 class="hero-slide-title">${title}</h2>`
                            : ""
                        }

                        ${text
                            ? `<div class="hero-slide-text">${text}</div>`
                            : ""
                        }

                    </div>
                </div>

            </div>
        `;
    }).join("");

    const arrows = showArrows
        ? `
            <button type="button" class="prev">❮</button>
            <button type="button" class="next">❯</button>
        `
        : "";

    const dots = showDots
        ? `
            <div class="slider-dots">
                ${slides.map((_, index) => `
                    <button
                        type="button"
                        class="dot ${index === 0 ? "active" : ""}"
                        data-slide="${index}">
                    </button>
                `).join("")}
            </div>
        `
        : "";

    return `
        <div
            class="widget-slider widget-hero-slider ${p.customClass || ""}"
            data-widget-id="${widget.id}"
            data-interval="${p.interval ?? 5000}"
            style="${p.customCss || ""}"
        >
            <div class="slider-slides">
                ${slidesHtml}
            </div>

            ${arrows}
            ${dots}
        </div>
    `;
}
};

    

console.log(
    "WIDGET REGISTRATI:",
    Object.keys(editor.widgets)
);






/*   
editor.widgets = {

   
image: {    //-----------------------------------------------


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
            type: "menuItems",
            label: "Voci menu",
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
        ${p.customCss ?? ""};
    " >

        ${links}
    </nav>
`;
    }
    },
    gallery : {   //-----------------------------------------------
    label: "Gallery",
    icon: "🖼️",
    defaultProps: {
        folder: "portfolio",
        columns: 3,
        gap: 16,
        radius: 8,
        height: 180
    },
    fields: {
        folder: { type: "galleryFolderSelect", label: "Cartella gallery", group: "contenuto" },
        columns: { type: "number", label: "Colonne", group: "stile" },
        gap: { type: "number", label: "Gap px", group: "stile" },
        radius: { type: "number", label: "Radius px", group: "stile" },
        height: { type: "number", label: "Altezza immagine px", group: "stile" }
    },
    render(widget) {
        const p = widget.props || {};
        const folder = p.folder || "portfolio";
        const columns = parseInt(p.columns ?? 3, 10);
        const gap = parseInt(p.gap ?? 16, 10);
        const radius = parseInt(p.radius ?? 8, 10);
        const height = parseInt(p.height ?? 180, 10);

        // Legge immagini dalla cache
        const images = editor.getGalleryImages(folder);

        if (!images.length) {
            // Carica immagini se cache vuota
            editor.loadGalleryImages(folder);
            return `<div class="widget-gallery-empty">Caricamento gallery: ${folder}...</div>`;
        }

        const items = images.map(src => `
            <div class="widget-gallery-item">
                <img src="${src}" data-src="${src}" class="gallery-lightbox-trigger" style="
                    width:100%;
                    display:block;
                    border-radius:${radius}px;
                    height:${height}px;
                    object-fit:cover;
                ">
            </div>
        `).join("");

        return `
            <div class="widget-gallery" style="
                display:grid;
                grid-template-columns:repeat(${columns}, 1fr);
                gap:${gap}px;
            ">
                ${items}
            </div>
        `;
    }
    },
    imageSlide : {   //-----------------------------------------------
    label: "Slider",
    icon: "🎞️",

    defaultProps: {
        folder: "portfolio",
        height: 400,
        interval: 5000,
        showArrows: true,
        showDots: true
    },

    fields: {

        folder: {
            type: "galleryFolderSelect",
            label: "Cartella immagini",
            group: "contenuto"
        },

        height: {
            type: "number",
            label: "Altezza slider px",
            group: "stile"
        },

        interval: {
            type: "number",
            label: "Intervallo autoplay ms",
            group: "stile"
        },

        showArrows: {
            type: "checkbox",
            label: "Mostra frecce",
            group: "stile"
        },

        showDots: {
            type: "checkbox",
            label: "Mostra indicatori",
            group: "stile"
        }
    },

    render(widget) {

        const p = widget.props || {};

        const folder = p.folder || "portfolio";
        const height = parseInt(p.height ?? 400, 10);
        const interval = parseInt(p.interval ?? 5000, 10);

        const showArrows = editor.toBoolean(
            p.showArrows,
            true
        );

        const showDots = editor.toBoolean(
            p.showDots,
            true
        );

        const images = editor.getGalleryImages(folder);

        if (!images.length) {


            editor.loadGalleryImages(folder);

            return `
                <div class="widget-slider-empty">
                    Caricamento slider: ${folder}...
                </div>
            `;
        }

        const slides = images.map((src, index) => `
            <div class="slide ${index === 0 ? "active" : ""}">
                <img
                    src="${src}"
                    alt=""
                    style="
                        width:100%;
                        height:${height}px;
                        display:block;
                        object-fit:cover;
                    "
                >
            </div>
        `).join("");

        const arrows = showArrows
            ? `
                <button type="button" class="prev">❮</button>
                <button type="button" class="next">❯</button>
            `
            : "";

        const dots = showDots
            ? `
                <div class="slider-dots">
                    ${images.map((src, index) => `
                        <button
                            type="button"
                            class="dot ${index === 0 ? "active" : ""}"
                            data-slide="${index}"
                            aria-label="Immagine ${index + 1}">
                        </button>
                    `).join("")}
                </div>
            `
            : "";

        return `
            <div
                class="widget-slider"
                data-widget-id="${widget.id}"
                data-interval="${interval}"
            >
                <div class="slider-slides">
                    ${slides}
                </div>

                ${arrows}
                ${dots}
            </div>
        `;
    }
    }
};
*/





// ==========================
// WIDGET GALLERY DEFINITIVO
// ==========================
editor.galleryAssets = editor.galleryAssets || {};

// Legge immagini già caricate
editor.getGalleryImages = function(folder) {
    return editor.galleryAssets[folder] || [];
};

//==================================================
// Carica immagini via PHP per il folder selezionato
//==================================================
editor.loadGalleryImages = function(folder) {

    if (!folder) {
        return Promise.resolve();
    }

    return fetch(
        `${window.FB_APP.appUrl}/FB-EDITOR/api/list-gallery-images.php?folder=${folder}&t=${Date.now()}`
    )
    .then(r => r.json())
    .then(data => {

        if (!data.success) return;

        editor.galleryAssets[folder] = data.images;
    })
    .catch(err => {
        console.error(
            "Errore caricamento immagini gallery:",
            err
        );
    });
};
//=========================
// Inizializza immagini della gallery al caricamento del widget
//=========================
editor.initGalleryWidget = function(widget) {
    const folder = widget.props?.folder;
    if (folder && !editor.galleryAssets[folder]) {
        editor.loadGalleryImages(folder);
    }
};

//=========================
// Handler select folder nell’inspector
//=========================
$(document).on("change", "#inspector [data-field='folder']", function() {
    const folder = $(this).val();
    const widget = editor.findWidgetById(editor.state.selectedId);
    if (!widget) return;

    widget.props.folder = folder;
    if (widget.type === "heroSlide") {

    editor.loadGalleryImages(value)
        .then(() => {

            editor.syncHeroSlidesFromFolder(widget);

            editor.render();

            editor.openWidgetInspector(widget.id);
        });

    return;
}
    editor.loadGalleryImages(folder); // Aggiorna il canvas
});

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

$("#tabs > div").each(function(i){
});
    const $panel = $("#widget-inspector");
    $panel.empty();

    if(!widget || !def){
        $panel.html("<p>Widget non trovato.</p>");
        return;
    }

$panel.append(`
    <div class="inspector-header">
       <h3 style="text-align:center; margin:10px; background: #222222; padding: 10px; margin: 0;">         
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

        if (field.type === "textarea" && Array.isArray(value)) {
            value = JSON.stringify(value, null, 2);
        }

        if (field.type === "menuItems" && !Array.isArray(value)) {
            value = def.defaultProps?.[fieldName] ?? [];
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

    const $panel = $("#widget-inspector");
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
    }

    if(field.type === "menuItems")    {
    let items = value;

    if (!Array.isArray(items)) {
        items = [];
    }

    let html = `
        <div class="menu-items-editor" data-field="${fieldName}">
    `;

    items.forEach((item, index) => {
        html += `
            <div class="menu-item-row" data-index="${index}" style="border:1px solid #ddd; padding:8px; margin-bottom:8px;">
                
                <input type="text"
                    data-menu-item-field="label"
                    value="${item.label ?? ""}"
                    placeholder="Label">

                <select data-menu-item-field="type">
                    <option value="anchor" ${item.type === "anchor" ? "selected" : ""}>Anchor</option>
                    <option value="page" ${item.type === "page" ? "selected" : ""}>Pagina</option>
                    <option value="url" ${item.type === "url" ? "selected" : ""}>URL</option>
                </select>

                <input type="text"
                    data-menu-item-field="target"
                    value="${item.target ?? ""}"
                    placeholder="Target">

                <button type="button" data-menu-item-up>↑</button>
                <button type="button" data-menu-item-down>↓</button>
                <button type="button" data-menu-item-delete>Elimina</button>
            </div>
        `;
    });

html += `
    <div class="menu-item-new" style="border:1px dashed #aaa; padding:8px; margin-top:10px;">
        <input type="text" data-menu-new-field="label" placeholder="Label">

        <select data-menu-new-field="type">
            <option value="anchor">Anchor</option>
            <option value="page">Pagina</option>
            <option value="url">URL</option>
        </select>

        <input type="text" data-menu-new-field="target" placeholder="Target">

        <button type="button" data-menu-item-add>Aggiungi</button>

    </div>
`;

    return html;
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
    if (field.type === "galleryFolderSelect") {
        const folders = editor.galleryFolders || [];
        let html = `<select data-field="${fieldName}">`;
        folders.forEach(folder => {
            html += `<option value="${folder}" ${value === folder ? "selected" : ""}>${folder}</option>`;
        });
        html += `</select>`;
        return html;
    }
    if (field.type === "checkbox") {

    const checked = editor.toBoolean(value, false)
        ? "checked"
        : "";

    return `
        <input
            type="checkbox"
            data-field="${fieldName}"
            ${checked}
        >
    `;
    }
    if (field.type === "heroSlides") {

    const slides = Array.isArray(value)
        ? value
        : [];

    let html = `
        <div class="hero-slides-editor">
    `;

    if (!slides.length) {
        html += `
            <div class="inspector-info">
                Nessun contenuto slide ancora associato.
            </div>
        `;
    }

    slides.forEach((slide, index) => {

        html += `
            <div class="hero-slide-editor-item"
                 data-slide-index="${index}">

                <strong>
                    Slide ${index + 1}
                </strong>

                <label>
                    Immagine
                </label>

                <input
                    type="text"
                    value="${slide.image || ""}"
                    data-hero-slide-field="image"
                    data-slide-index="${index}"
                    readonly
                >

                <label>
                    Titolo
                </label>

                <input
                    type="text"
                    value="${slide.title || ""}"
                    data-hero-slide-field="title"
                    data-slide-index="${index}"
                >

                <label>
                    Testo
                </label>

                <textarea
                    data-hero-slide-field="text"
                    data-slide-index="${index}"
                >${slide.text || ""}</textarea>

            </div>
        `;
    });

    html += `</div>`;

    return html;
    }
        
    setTimeout(async () => {
        const html = await editor.renderImagePicker(fieldName, value);
        $("#widget-inspector")
            .find(`[data-image-picker="${fieldName}"]`)
            .html(html);
    }, 0);

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
              <span
                class="widget-drag-handle material-symbols-outlined"
                title="Sposta widget"
            >
                drag_indicator
            </span>
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
        url = "/FB-MONOPAGE-NEW/assets/images/" + url;
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

//=================================
//  carica cartelle per gallery
//================================= 
editor.galleryFolders = []; 
editor.getAssetsByFolder = async function(folder) {
    if (!folder) return [];
    try {
        const res = await fetch(`${window.FB_APP.appUrl}/FB-EDITOR/api/list-gallery-images.php?folder=${folder}`);
        const data = await res.json();
        if (!data.success) return [];
        return data.images;  // Assumiamo che list-gallery-images.php restituisca { success: true, images: [...] }
    } catch(e) {
        console.error("Errore getAssetsByFolder:", e);
        return [];
    }
};
//========================================
// Open widget inspector
//========================================
editor.openWidgetInspector = async function(id){

    const widget = editor.findWidgetById(id);
    if (!widget) return;

    if (widget.type === "heroSlide") {

        const folder = widget.props?.folder || "";

        if (folder) {

            const images = editor.getGalleryImages(folder);

            // Se la cache non contiene ancora immagini,
            // aspetta il caricamento
            if (!images.length) {
                await editor.loadGalleryImages(folder);
            }

            editor.syncHeroSlidesFromFolder(widget);
        }
    }

    editor.renderInspector(
        widget,
        editor.widgets[widget.type]
    );
};


//==================================================
// Sync immagini folder -> props.slides Hero Slider
//==================================================
editor.syncHeroSlidesFromFolder = function(widget) {

    if (!widget || widget.type !== "heroSlide") {
        return;
    }

    const p = widget.props || {};
    const folder = p.folder || "";

    if (!folder) return;

    const images = editor.getGalleryImages(folder) || [];

    if (!images.length) {
        editor.loadGalleryImages(folder);
        return;
    }

    const oldSlides = Array.isArray(p.slides)
        ? p.slides
        : [];

    const oldMap = {};

    oldSlides.forEach(slide => {
        if (slide.image) {
            oldMap[slide.image] = slide;
        }
    });

    p.slides = images.map(src => {

        if (oldMap[src]) {
            return oldMap[src];
        }

        return {
            image: src,
            title: "",
            text: ""
        };
    });

    widget.props = p;

    editor.state.isDirty = true;
};