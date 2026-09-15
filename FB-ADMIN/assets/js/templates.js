async function loadTemplates() {

    const container = document.querySelector("#templates-list");

    if (!container) return;

    const response = await fetch(
        window.APP_URL + "/FB-ADMIN/api/list-templates.php"
    );

    const data = await response.json();

    if (!data.success) {
        container.innerHTML = "Errore caricamento templates";
        return;
    }

    let html = `
        <div class="pages-table">

            <div class="pages-row pages-header">
                <div>Scelta</div>
                <div>Template</div>
            </div>
    `;

    data.templates.forEach(function (template) {

        html += `
            <div class="pages-row">

                <div>
                    <input
                        type="radio"
                        name="selected-template"
                        value="${template}">
                </div>

                <div>
                    ${template}
                </div>

            </div>
        `;
    });

    html += `</div>`;

    container.innerHTML = html;
}


//===========================================
// AZIONI TEMPLATES
//===========================================

document.addEventListener("click", async function (event) {

//=======================================
// NUOVO TEMPLATE
//=======================================
if (event.target.closest("#template-new")) {

    const name = prompt("Nome del nuovo template:");

    if (!name) return;

    const cleanName = name.trim();

    if (cleanName === "") return;

    window.location.href =
        window.APP_URL +
        "/FB-EDITOR/editor/editor.php?template=new&name=" +
        encodeURIComponent(cleanName);

    return;
}
    //=======================================
    // MODIFICA TEMPLATE
    //=======================================
    if (event.target.closest("#template-edit")) {

        const selected = document.querySelector(
            'input[name="selected-template"]:checked'
        );

        if (!selected) {
            alert("Seleziona un template da modificare.");
            return;
        }

        const template = selected.value;

        window.location.href =
            window.APP_URL +
            "/FB-EDITOR/editor/editor.php?template=" +
            encodeURIComponent(template);

        return;
    }


    //=======================================
    // CANCELLA TEMPLATE
    //=======================================
    if (event.target.closest("#template-delete")) {

        const selected = document.querySelector(
            'input[name="selected-template"]:checked'
        );

        if (!selected) {
            alert("Seleziona un template da cancellare.");
            return;
        }

        const template = selected.value;

        const ok = confirm(
            'Vuoi cancellare il template "' + template + '"?'
        );

        if (!ok) return;

        const response = await fetch(
            window.APP_URL + "/FB-ADMIN/api/delete-template.php",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    template: template
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

        loadTemplates();

        return;
    }

});