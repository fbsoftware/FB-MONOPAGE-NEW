//=========================================================
// Carica sezioni globali
//=========================================================
async function loadGlobals() {

    const container = document.querySelector("#global-list");

    if (!container) return;

    const response = await fetch(
        window.APP_URL + "/FB-EDITOR/api/list-global-sections.php"
    );

    const data = await response.json();

    if (!data.success) {
        container.innerHTML = "Errore caricamento globali";
        return;
    }

    let html = `
        <div class="pages-table">

            <div class="pages-row pages-header">
                <div>Scelta</div>
                <div>Sezione</div>
            </div>
    `;

    data.globals.forEach(global => {

        html += `
            <div class="page-row">

                <input
                    type="radio"
                    name="selected-global"
                    class="global-radio"
                    value="${global.type}">

                <span class="page-title">
                    ${global.name}
                </span>

            </div>
        `;

    });

    html += `</div>`;

    container.innerHTML = html;

//=======================================
// MODIFICA GLOBALE
//=======================================
document.addEventListener("click", function(event) {

    if (event.target.closest("#global-edit")) {

        const selected = document.querySelector(
            'input[name="selected-global"]:checked'
        );

        if (!selected) {
            alert("Seleziona una sezione globale da modificare.");
            return;
        }

        window.location.href =
            window.APP_URL +
            "/FB-EDITOR/editor/editor.php?global=" +
            encodeURIComponent(selected.value);

        return;
    }

});
}