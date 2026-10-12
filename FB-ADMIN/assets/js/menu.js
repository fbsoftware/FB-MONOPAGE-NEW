//=========================================================
// FB-ADMIN - MENU
//=========================================================

let adminSiteMenu = [];
let adminAvailablePages = [];


//---------------------------------------------------------
// Caricamento iniziale
//---------------------------------------------------------
async function loadMenu(){

    await loadAvailablePages();
    await loadSiteMenu();

    renderAvailablePages();
    renderSiteMenu();

}

//---------------------------------------------------------
// Carica pagine disponibili
//---------------------------------------------------------
async function loadAvailablePages(){

    const res = await fetch(
        window.APP_URL + "/FB-EDITOR/api/list-pages.php"
    );

    //const data = await res.json();
    const text = await res.text();

    const data = JSON.parse(text);

        if(data.success && Array.isArray(data.pages)){
            adminAvailablePages = data.pages;
        } else {
            adminAvailablePages = [];
        }

}

//---------------------------------------------------------
// Carica menu salvato
//---------------------------------------------------------
async function loadSiteMenu(){

    const res = await fetch(
        window.APP_URL + "/FB-EDITOR/api/load-menu.php"
    );

    const data = await res.json();

if(data.success && Array.isArray(data.menu)){

    adminSiteMenu = data.menu;

    console.log(
        adminSiteMenu
    );

} else {

    adminSiteMenu = [];

    console.log(
        "MENU VUOTO"
    );
}

}


//---------------------------------------------------------
// Render pagine disponibili
//---------------------------------------------------------
function renderAvailablePages(){

    const box = document.querySelector(
        "#available-pages-panel"
    );

    if(!box) return;


    let html = `
        <div class="menu-box">

            <h3 class="menu-section-title">
                Pagine disponibili
            </h3>
        `;

adminAvailablePages.forEach(page => {

const presente = adminSiteMenu.some(
    item => item.target === page.page
);

    html += `
        <div class="available-page-item">

            <span>
                ${page.title}
            </span>

            <button
                class="menu-action-btn menu-add-page"
                data-page="${page.page}"
                data-title="${page.title}"
                ${presente ? "disabled" : ""}>
                
                ${presente ? "Nel menu" : "Aggiungi"}

            </button>

        </div>
    `;

});

html += `
</div>
`;
    box.innerHTML = html;

}
//---------------------------------------------------------
// Render menu attuale
//---------------------------------------------------------
function renderSiteMenu(){
    const box = document.querySelector(
        "#site-menu-builder"
    );

    if(!box) return;


    let html = `
        <div class="menu-box">

            <h3 class="menu-section-title">
                Menu attuale
            </h3>
        `;

html += `

<div class="menu-header">

    <span class="menu-title">
        Titolo
    </span>

    <span class="menu-type">
        Tipo
    </span>

    <span class="menu-target">
        Destinazione
    </span>

    <span class="menu-level">
        Liv.
    </span>

    <span class="menu-buttons">
        Cambia livello
    </span>

    <span class="menu-buttons">
        Sposta
    </span>

    <span class="menu-actions">
        Azioni
    </span>

</div>

`;


adminSiteMenu.forEach((item, index) => {

html += `
    <div class="menu-item">

        <span class="menu-title">
            ${item.title}
        </span>

        <small class="menu-type">
            ${item.type}
        </small>

        <small class="menu-target">
            ${item.target}
        </small>

        <span class="menu-level">
            ${item.level ?? 0}
        </span>

    <button
        type="button"
        class="menu-level-btn menu-level-down"
        data-index="${index}">
        −
    </button>

    <button
        type="button"
        class="menu-level-btn menu-level-up"
        data-index="${index}">
        +
    </button>

<button
    class="menu-icon-btn menu-move-up"
    data-index="${index}">
    ↑
</button>

<button
    class="menu-icon-btn menu-move-down"
    data-index="${index}">
    ↓
</button>

<button
    class="menu-action-btn menu-edit-item"
    data-index="${index}">
    Modifica
</button>

<button
    class="menu-action-btn menu-delete-item"
    data-index="${index}">
    Rimuovi
</button>

        </div>
    `;

});

html += `
        </div>
        `;
    box.innerHTML = html;

}

//---------------------------------------------------------
// Aggiungi pagina al menu
//---------------------------------------------------------
document.addEventListener(
    "click",
    function(e){

        const btn = e.target.closest(".menu-add-page");

        if(!btn) {
            return;
        }

        console.log("ENTRO NEL PUSH");

        adminSiteMenu.push({

            title: btn.dataset.title,
            type: "page",
            target: btn.dataset.page,
            level: 0

        });

        console.log("MENU DOPO AGGIUNTA:", adminSiteMenu);

        renderSiteMenu();
        renderAvailablePages();

    }
);

//---------------------------------------------------------
// Rimuovi voce dal menu
//---------------------------------------------------------
document.addEventListener(
    "click",
    function(e){

        const btn = e.target.closest(".menu-delete-item");

        if(!btn) return;


        const index = Number(btn.dataset.index);


        adminSiteMenu.splice(index, 1);


        renderSiteMenu();
        renderAvailablePages();
    }
);

//---------------------------------------------------------
// Sposta voce verso l'alto
//---------------------------------------------------------
document.addEventListener(
    "click",
    function(e){

        const btn = e.target.closest(".menu-move-up");

        if(!btn) return;


        const index = Number(btn.dataset.index);


        if(index <= 0) return;


        [
            adminSiteMenu[index - 1],
            adminSiteMenu[index]
        ] = [
            adminSiteMenu[index],
            adminSiteMenu[index - 1]
        ];


        renderSiteMenu();

    }
);


//---------------------------------------------------------
// Sposta voce verso il basso
//---------------------------------------------------------
document.addEventListener(
    "click",
    function(e){

        const btn = e.target.closest(".menu-move-down");

        if(!btn) return;


        const index = Number(btn.dataset.index);


        if(index >= adminSiteMenu.length - 1) return;


        [
            adminSiteMenu[index],
            adminSiteMenu[index + 1]
        ] = [
            adminSiteMenu[index + 1],
            adminSiteMenu[index]
        ];


        renderSiteMenu();

    }
);

//---------------------------------------------------------
// Salva il menu
//---------------------------------------------------------
async function saveMenu(){

    const res = await fetch(
        window.APP_URL +
        "/FB-EDITOR/api/save-menu.php",
        {
            method: "POST",
            headers:{
                "Content-Type":"application/json"
            },
            body: JSON.stringify({
                menu: adminSiteMenu
            })
        }
    );

    //const data = await res.json();
    const text = await res.text();
    console.log("RISPOSTA SAVE MENU:", text);
    const data = JSON.parse(text);
    // ------------------------------

    if(data.success){
        alert("Menu salvato");
    } else {
        alert("Errore: " + data.error);
    }
}

document.addEventListener(
    "click",
    function(e){

        if(!e.target.closest("#menu-save"))
            return;

        saveMenu();

    }
);

//---------------------------------------------------------
// Modifica titolo voce menu
//---------------------------------------------------------
document.addEventListener(
    "click",
    function(e){

        const btn = e.target.closest(".menu-edit-item");

        if(!btn) return;


        const index = Number(btn.dataset.index);

        const item = adminSiteMenu[index];


        const nuovoTitolo = prompt(
            "Titolo voce menu:",
            item.title
        );

        if(!nuovoTitolo) return;


        let sceltaTipo = prompt(
            "Tipo voce:\n\n" +
            "1 = Pagina\n" +
            "2 = Anchor\n" +
            "3 = Link esterno",

            item.type === "anchor"
                ? "2"
                : item.type === "external"
                    ? "3"
                    : "1"
        );


        if(!sceltaTipo) return;


        let nuovoTipo;

        switch(sceltaTipo){

            case "2":
                nuovoTipo = "anchor";
                break;

            case "3":
                nuovoTipo = "external";
                break;

            default:
                nuovoTipo = "page";
        }


        item.title = nuovoTitolo;
        item.type = nuovoTipo;


        //=================================================
        // TARGET
        //=================================================

        if(nuovoTipo !== "page"){

            const nuovoTarget = prompt(
                "Destinazione:",
                item.target || ""
            );

            if(nuovoTarget === null) return;

            item.target = nuovoTarget;

        }


        // elimina eventuale vecchio campo
        delete item.page;


        renderSiteMenu();

    }
);
//---------------------------------------------------------
// Diminuisci livello voce menu
//---------------------------------------------------------
document.addEventListener(
    "click",
    function(e){

        const btn = e.target.closest(".menu-level-down");

        if(!btn) return;

        const index = Number(btn.dataset.index);

        const item = adminSiteMenu[index];

        if(!item) return;

        const level = Number(item.level ?? 0);

        if(level > 0){

            item.level = level - 1;

        }

        renderSiteMenu();

    }
);

//---------------------------------------------------------
// Aumenta livello voce menu
//---------------------------------------------------------
document.addEventListener(
    "click",
    function(e){

        const btn = e.target.closest(".menu-level-up");

        if(!btn) return;

        const index = Number(btn.dataset.index);

        const item = adminSiteMenu[index];

        if(!item) return;

        const level = Number(item.level ?? 0);

        item.level = level + 1;

        renderSiteMenu();

    }
);