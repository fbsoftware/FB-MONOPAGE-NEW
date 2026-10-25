//===========================================
// CARICA CARTELLE SLIDE
//===========================================

async function loadSlides() {

    const container =
         document.querySelector("#slide-folders-list");

    if (!container) return;

    try {

        const response = await fetch(
            window.APP_URL +
            "/FB-ADMIN/api/list-section-folders.php"
        );

        const data = await response.json();

        if (!data.success) {

            container.innerHTML =
                "Errore caricamento cartelle slide";

            return;
        }

        let html = `
            <div class="pages-table">

                <div class="pages-row pages-header">
                    <div>Scelta</div>
                    <div>Cartella slider</div>
                </div>
        `;

        data.folders.forEach(function(folder) {

            html += `
                <div class="pages-row">

                    <div>
                        <input
                            type="radio"
                            name="selected-slide-folder"
                            value="${folder}">
                    </div>

                    <div>
                        ${folder}
                    </div>

                </div>
            `;
        });

        html += `</div>`;

        container.innerHTML = html;

    } catch(error) {

        console.error(
            "Errore caricamento cartelle slide:",
            error
        );

        container.innerHTML =
            "Errore caricamento cartelle slide";
    }
}

//===========================================
// SCELTA CARTELLA SLIDER
//===========================================

document.addEventListener("change", async function(e){

    if(e.target.name !== "selected-slide-folder"){
        return;
    }

    const folder = e.target.value;

    console.log(
        "CARTELLA SLIDER SELEZIONATA:",
        folder
    );

    const container =
        document.querySelector("#slide-files-list");

    if(!container) return;

    try {

        const response = await fetch(
            window.APP_URL +
            "/FB-EDITOR/api/list-section-slides.php?folder=" +
            encodeURIComponent(folder)
        );

        const data = await response.json();

        console.log(
            "SLIDES CARTELLA:",
            data
        );

        if(!data.success){

            container.innerHTML =
                "Errore caricamento slides";

            return;
        }

        let html = `
            <div class="pages-table">

                <div class="pages-row pages-header">
                    <div>Scelta</div>
                    <div>Slide</div>
                </div>
        `;

        data.files.forEach(function(file){

            html += `
                <div class="pages-row">

                    <div>
                        <input
                            type="radio"
                            name="selected-slide"
                            value="${file}">
                    </div>

                    <div>
                        ${file}
                    </div>

                </div>
            `;

        });

        html += `</div>`;

        container.innerHTML = html;

    } catch(error) {

        console.error(
            "Errore caricamento slides:",
            error
        );

        container.innerHTML =
            "Errore caricamento slides";
    }

});
//===========================================
// MODIFICA SLIDE
//===========================================

document.addEventListener("click", function(e){

    if(e.target.closest("#slide-edit") === null){
        return;
    }

    const folderInput =
        document.querySelector(
            'input[name="selected-slide-folder"]:checked'
        );

    const slideInput =
        document.querySelector(
            'input[name="selected-slide"]:checked'
        );

    if(!folderInput){

        alert("Seleziona prima una cartella.");

        return;
    }

    if(!slideInput){

        alert("Seleziona prima una slide.");

        return;
    }

    const folder = folderInput.value;
    const file = slideInput.value;

window.location.href =
    window.APP_URL +
    "/FB-EDITOR/editor/editor.php?template=" +
    encodeURIComponent(folder) +
    "&slide=" +
    encodeURIComponent(file);

});
//===========================================
// NUOVA SLIDE
//===========================================

document.addEventListener("click", function(e){

    if(e.target.closest("#slide-new") === null){
        return;
    }

    const folderInput =
        document.querySelector(
            'input[name="selected-slide-folder"]:checked'
        );

    if(!folderInput){

        alert("Seleziona prima una cartella.");

        return;
    }

    const folder = folderInput.value;

    console.log(
        "NUOVA SLIDE NELLA CARTELLA:",
        folder
    );

    window.location.href =
        window.APP_URL +
        "/FB-EDITOR/editor/editor.php?template=" +
        encodeURIComponent(folder) +
        "&slide=new";

});
//===========================================
// CANCELLA SLIDE
//===========================================

document.addEventListener("click", async function(e){

    if(e.target.closest("#slide-delete") === null){
        return;
    }

    const folderInput =
        document.querySelector(
            'input[name="selected-slide-folder"]:checked'
        );

    const slideInput =
        document.querySelector(
            'input[name="selected-slide"]:checked'
        );

    if(!folderInput){

        alert("Seleziona prima una cartella.");

        return;
    }

    if(!slideInput){

        alert("Seleziona prima una slide.");

        return;
    }

    const folder = folderInput.value;
    const file = slideInput.value;

    const ok = confirm(
        'Vuoi cancellare la slide "' +
        file +
        '" dalla cartella "' +
        folder +
        '"?'
    );

    if(!ok){
        return;
    }

    console.log(
        "CANCELLA SLIDE:",
        folder,
        file
    );

    try {

        const response = await fetch(
            window.APP_URL +
            "/FB-EDITOR/api/delete-section-slide.php",
            {
                method: "POST",

                headers: {
                    "Content-Type": "application/json"
                },

                body: JSON.stringify({
                    folder: folder,
                    file: file
                })
            }
        );

        const data = await response.json();

        console.log(
            "DELETE SLIDE RESULT:",
            data
        );

        if(!data.success){

            alert(
                "Errore cancellazione: " +
                (data.error || "errore sconosciuto")
            );

            return;
        }

        alert(
            'Slide "' +
            file +
            '" cancellata.'
        );

        // Ricarica l'elenco delle slide
        document.querySelector(
            'input[name="selected-slide-folder"]:checked'
        ).dispatchEvent(new Event("change"));

    } catch(error) {

        console.error(
            "Errore cancellazione slide:",
            error
        );

        alert(
            "Errore cancellazione slide: " +
            error.message
        );
    }

});