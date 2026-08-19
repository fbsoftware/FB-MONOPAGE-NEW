//=========================================================
// Render the site menu builder interface
//=========================================================
editor.renderSiteMenuBuilder = function(){

    const $nav = $("#site-menu-builder");
    $nav.empty();

    let html = `<div class="menu-builder-list">`;

    (editor.siteMenu || []).forEach((item, index) => {

        const current = "nav-current-page" === item.page ? "current" : "";

html += `
    <div class="menu-item ${current}"
         data-index="${index}"
         data-page="${item.page}"
         style="margin-left:${item.level * 24}px;">

        <span class="menu-drag">⋮⋮</span>

        <button class="menu-left" data-index="${index}">←</button>
        <button class="menu-right" data-index="${index}">→</button>
        <button class="menu-delete" data-index="${index}">✕</button>

        <span class="menu-title">${item.title}</span>
        <span class="menu-page">${item.page}</span>
    </div>
`;
    });

    html += `</div>`;

    $nav.html(html);

    editor.initSiteMenuSortable();
};

//=========================================================
// Handle click on menu items to load the corresponding page
//=========================================================
$(document).on("click", ".menu-item", function(e){

    if($(e.target).is("button")) return;
editor.navState.isDirty = true;
    const page = $(this).data("page");
    if(!page) return;

    editor.loadPage(page);
});

//=========================================================
// Delete menu item and its children
//=========================================================
$(document).on("click", ".menu-delete", function(e){
    e.preventDefault();
    e.stopPropagation();
editor.navState.isDirty = true;
    const index = parseInt($(this).data("index"), 10);
    if(isNaN(index)) return;

    const item = editor.siteMenu[index];
    if(!item) return;

    const ok = confirm(`Eliminare "${item.title}" dal menu?`);
    if(!ok) return;

    const baseLevel = item.level || 0;
    let deleteCount = 1;

    for(let i = index + 1; i < editor.siteMenu.length; i++){
        const nextLevel = editor.siteMenu[i].level || 0;

        if(nextLevel > baseLevel){
            deleteCount++;
        } else {
            break;
        }
    }

    editor.siteMenu.splice(index, deleteCount);

    editor.renderSiteMenuBuilder();
    editor.renderAvailablePages();
});
//=========================================================
// Add a new page to the site menu
//========================================================= 
editor.addPageToMenu = function(title, page){
editor.navState.isDirty = true;
    if(!editor.siteMenu) editor.siteMenu = [];

    editor.siteMenu.push({
        title: title,
        page: page,
        level: 0
    });

    editor.renderSiteMenuBuilder();
};

//=========================================================
// Initialize jQuery UI sortable on the menu builder list
//=========================================================
editor.initSiteMenuSortable = function(){
editor.navState.isDirty = true;
    if($(".menu-builder-list").data("ui-sortable")){
        $(".menu-builder-list").sortable("destroy");
    }

    $(".menu-builder-list").sortable({
        items: "> .menu-item",
        placeholder: "menu-item-placeholder",

        update: function(){
            editor.syncSiteMenuOrderFromDOM();
            editor.navState.isDirty = true;
            editor.renderSiteMenuBuilder();
        }
    });
};



//=========================================================
// Sync the order of the site menu from the DOM after sorting
//=========================================================
editor.syncSiteMenuOrderFromDOM = function(){
editor.navState.isDirty = true;
    const oldMenu = [...editor.siteMenu];
    const newMenu = [];

    $(".menu-builder-list > .menu-item").each(function(){

        const index = parseInt($(this).attr("data-index"), 10);

        if(!isNaN(index) && oldMenu[index]){
            newMenu.push(oldMenu[index]);
        }
    });

    editor.siteMenu = newMenu;
};

//=========================================================
// Handle click on move right button to increase the level of the menu item
//=========================================================
$(document).on("click", ".menu-right", function(e){
    e.stopPropagation();
editor.navState.isDirty = true;
    const index = parseInt($(this).data("index"), 10);
    if(isNaN(index)) return;

    const item = editor.siteMenu[index];

    item.level = Math.min((item.level || 0) + 1, 2);

    editor.renderSiteMenuBuilder();
});


//=========================================================
// Handle click on move left button to decrease the level of the menu item
//=========================================================
$(document).on("click", ".menu-left", function(e){
    e.stopPropagation();
editor.navState.isDirty = true;
    const index = parseInt($(this).data("index"), 10);
    if(isNaN(index)) return;

    const item = editor.siteMenu[index];

    item.level = Math.max((item.level || 0) - 1, 0);

    editor.renderSiteMenuBuilder();
});

//=========================================================
// Build the HTML for the site menu 
// based on the navigation data structure
//=========================================================
editor.buildMenuHtml = function(items){
    let html = "";
    let prevLevel = 0;

    items.forEach((item, i) => {
        const level = item.level || 0;

        if(i === 0){
            html += "<ul>";
        }

        if(level > prevLevel){
            html += "<ul>";
        }

        if(level < prevLevel){
            for(let j = prevLevel; j > level; j--){
                html += "</li></ul>";
            }
        } else if(i > 0) {
            html += "</li>";
        }

        html += `<li><a href="${item.page}.html">${item.title} target="_blank"</a>`;

        prevLevel = level;
    });

    for(let j = prevLevel; j >= 0; j--){
        html += "</li></ul>";
    }

    return html;
};

//=================================
// save site menu
//=================================
editor.saveSiteMenu = async function(){

    try {
        const res = await fetch("/FB-MONOPAGE-NEW/api/save-menu.php", 
        {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ menu: editor.siteMenu })
        });

console.log("Response from save-menu.php:", res);

        const data = await res.json();

        if(!data.success){
            alert("Errore salvataggio menu: " + (data.error || "Errore sconosciuto"));
            return;
        }

        console.log("Menu salvato:", data);
        alert("Menu salvato correttamente");
    }
    catch(err){
        console.error("Errore save menu:", err);
        alert("Errore durante il salvataggio del menu");
    }
};

//=========================================================
// Handle click on save site menu button to save the menu structure
//=========================================================
$(document).on("click", "#save-site-menu", function(){
    editor.saveSiteMenu();
    editor.navState.isDirty = false;
});


//=========================================================
// Load the site menu structure from the server when the editor initializes
//=========================================================
editor.loadSiteMenu = async function(){
    editor.navState.isDirty = true;
    try {
        const res = await fetch("/FB-MONOPAGE-NEW/api/load-menu.php");
        const data = await res.json();
 
         if(data.success && data.menu && data.menu.length){
            editor.siteMenu = data.menu;
        } else {
            editor.siteMenu = [
                {
                    title: "Home",
                    page: "home",
                    level: 0
                }
            ];
        }

        editor.renderSiteMenuBuilder();
    }
    catch(err){
        console.error("Errore load menu:", err);

        editor.siteMenu = [
            {
                    title: "Home",
                    page: "home",
                    level: 0
                }
        ];

        editor.renderSiteMenuBuilder();
    }
};

//=========================================================
// Load available pages from /data
//=========================================================
editor.loadAvailablePages = async function(){
editor.navState.isDirty = true;
    try {
        const res = await fetch("/FB-MONOPAGE-NEW/api/list-pages.php");
        const data = await res.json();

        if(data.success && Array.isArray(data.pages)){
            editor.availablePages = data.pages;
        } else {
            editor.availablePages = [];
            console.warn("Pagine non caricate:", data.error || "Errore sconosciuto");
        }

        editor.renderAvailablePages();
    }
    catch(err){
        console.error("Errore loadAvailablePages:", err);
        editor.availablePages = [];
        editor.renderAvailablePages();
    }
};

//=========================================================
// Render available pages list
//=========================================================
editor.renderAvailablePages = function(){
editor.navState.isDirty = true;
    const $panel = $("#available-pages-panel");
    $panel.empty();

    let html = `
        <div class="available-pages-box">
            <h3>Pagine disponibili</h3>
    `;

    if(!editor.availablePages || !editor.availablePages.length){
        html += `<p class="empty-pages">Nessuna pagina trovata.</p>`;
    } else {
        editor.availablePages.forEach(page => {

            const alreadyInMenu = (editor.siteMenu || []).some(item => item.page === page.page);
            const disabled = alreadyInMenu ? "disabled" : "";
            const label = alreadyInMenu ? "Già nel menu" : "+ Pagina";

            html += `
                <div class="available-page-item"
                     data-page="${page.page}"
                     data-title="${page.title}">

                    <div class="available-page-info">
                        <div class="available-page-title">${page.title}</div>
                        <div class="available-page-slug">${page.page}</div>
                    </div>

                    <button class="add-page-to-menu"
                            data-page="${page.page}"
                            data-title="${page.title}"
                            ${disabled}>
                        ${label}
                    </button>
                </div>
            `;
        });
    }

    html += `</div>`;

    $panel.html(html);
};

//=========================================================
// Add available page to site menu
//=========================================================
$(document).on("click", ".add-page-to-menu", function(e){
    e.preventDefault();
    e.stopPropagation();
editor.navState.isDirty = true;
    const page = $(this).data("page");
    const title = $(this).data("title");

    if(!page || !title) return;

    const alreadyExists = (editor.siteMenu || []).some(item => item.page === page);

    if(alreadyExists){
        alert("Questa pagina è già presente nel menu.");
        return;
    }

    editor.addPageToMenu(title, page);
    editor.renderAvailablePages();
});

//=========================================================
// Add a new page to the site menu
//========================================================= 
editor.addPageToMenu = function(title, page){
editor.navState.isDirty = true;
    if(!editor.siteMenu) editor.siteMenu = [];

    editor.siteMenu.push({
        title: title,
        page: page,
        level: 0
    });

    editor.renderSiteMenuBuilder();
};

//=========================================================
// salvataggio
//========================================================= 
$(document).on("click", "#nav-exit", function(){

    if(editor.navState.isDirty){
        if(!confirm("Vuoi uscire senza salvare il menu?")){
            return;
        }
    }

    window.location.href = "/FB-MONOPAGE-NEW/admin/admin.php";
});