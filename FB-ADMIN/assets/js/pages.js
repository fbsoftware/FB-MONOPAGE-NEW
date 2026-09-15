async function loadPages() {

    const container = document.querySelector("#pages-list");

    if (!container) return;

    const response = await fetch(
        window.APP_URL + "/FB-ADMIN/api/list-pages.php"
    );

    const data = await response.json();

    if (!data.success) {
        container.innerHTML = "Errore caricamento pagine";
        return;
    }

    let html = `
        <div class="pages-table">

            <div class="pages-row pages-header">
                <div>Scelta</div>
                <div>Pagina</div>
            </div>
    `;

    data.pages.forEach(function (page) {

        html += `
            <div class="pages-row">

                <div>
                    <input
                        type="radio"
                        name="selected-page"
                        value="${page}">
                </div>

                <div>
                    ${page}
                </div>

            </div>
        `;
    });

    html += `</div>`;

    container.innerHTML = html;
}


//===========================================
// AZIONI PAGINE
//===========================================

document.addEventListener("click", async function (event) {

    //=======================================
    // NUOVA PAGINA
    //=======================================
    if (event.target.closest("#page-new")) {

        const page = prompt("Nome della nuova pagina:");

        if (!page) return;

        const cleanPage = page.trim();

        if (cleanPage === "") return;

        window.location.href =
            window.APP_URL +
            "/FB-EDITOR/editor/editor.php?page=" +
            encodeURIComponent(cleanPage);

        return;
    }


    //=======================================
    // MODIFICA PAGINA
    //=======================================
    if (event.target.closest("#page-edit")) {

        const selected = document.querySelector(
            'input[name="selected-page"]:checked'
        );

        if (!selected) {
            alert("Seleziona una pagina da modificare.");
            return;
        }

        window.location.href =
            window.APP_URL +
            "/FB-EDITOR/editor/editor.php?page=" +
            encodeURIComponent(selected.value);

        return;
    }


    //=======================================
    // CANCELLA PAGINA
    //=======================================
    if (event.target.closest("#page-delete")) {

        const selected = document.querySelector(
            'input[name="selected-page"]:checked'
        );

        if (!selected) {
            alert("Seleziona una pagina da cancellare.");
            return;
        }

        const page = selected.value;

        const ok = confirm(
            'Vuoi cancellare la pagina "' + page + '"?'
        );

        if (!ok) return;

        const response = await fetch(
            window.APP_URL + "/FB-ADMIN/api/delete-page.php",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    page: page
                })
            }
        );

        const data = await response.json();

        if (!data.success) {
            alert(
                data.message ||
                "Errore durante la cancellazione."
            );
            return;
        }

        loadPages();

        return;
    }

});