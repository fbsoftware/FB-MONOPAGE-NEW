//=========================================================
// FB-ADMIN - MENU
//=========================================================

let adminSiteMenu = [];
let adminAvailablePages = [];


//---------------------------------------------------------
// Caricamento iniziale
//---------------------------------------------------------
async function loadMenu(){

    console.log("MENU VIEW CARICATA");

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

console.log("RISPOSTA LIST-PAGES:", text);

const data = JSON.parse(text);

console.log("PAGINE:", data);

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
    console.log("MENU:", data);

if(data.success && Array.isArray(data.menu)){

    adminSiteMenu = data.menu;
    console.log("ADMIN SITE MENU DOPO ASSEGNAZIONE:", adminSiteMenu);

    console.log(
        "MENU CARICATO:",
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
        <h3>Pagine disponibili</h3>
    `;


adminAvailablePages.forEach(page => {

    const presente = adminSiteMenu.some(
        item => item.page === page.page
    );

    html += `
        <div class="available-page-item">

            <span>
                ${page.title}
            </span>

            <button
                class="menu-add-page"
                data-page="${page.page}"
                data-title="${page.title}"
                ${presente ? "disabled" : ""}>
                
                ${presente ? "Nel menu" : "Aggiungi"}

            </button>

        </div>
    `;

});

    box.innerHTML = html;

}
//---------------------------------------------------------
// Render menu attuale
//---------------------------------------------------------
function renderSiteMenu(){
console.log("RENDER SITE MENU:", adminSiteMenu);
    const box = document.querySelector(
        "#site-menu-builder"
    );

    if(!box) return;


    let html = `
        <h3>Menu attuale</h3>
    `;


adminSiteMenu.forEach((item, index) => {

    html += `
        <div class="menu-item">

            <span style="width: 100px; display: inline-block; overflow: hidden; text-overflow: ellipsis;">
                ${item.title}
            </span>

            <small style="width: 100px; display: inline-block; overflow: hidden; text-overflow: ellipsis;">
                ${item.page}
            </small>

            <button
                class="menu-move-up"
                data-index="${index}">
                ↑
            </button>

            <button
                class="menu-move-down"
                data-index="${index}">
                ↓
            </button>

            <button
                class="menu-delete-item"
                data-index="${index}">
                Rimuovi
            </button>

        </div>
    `;

});

//console.log("HTML MENU:", html);
    box.innerHTML = html;

}

//---------------------------------------------------------
// Aggiungi pagina al menu
//---------------------------------------------------------
document.addEventListener(
    "click",
    function(e){

        const btn = e.target.closest(".menu-add-page");

        console.log("BTN TROVATO:", btn);

        if(!btn) {
            console.log("ESCO DAL RETURN");
            return;
        }

        console.log("ENTRO NEL PUSH");

        adminSiteMenu.push({

            title: btn.dataset.title,
            page: btn.dataset.page,
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