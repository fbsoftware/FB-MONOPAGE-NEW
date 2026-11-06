// ==========================================
// FB-ADMIN - LIBRERIA MEDIA
// ==========================================

async function loadMedia() {

    const folders = document.getElementById("admin-media-folders");
    const grid = document.getElementById("admin-media-grid");
    const details = document.getElementById("admin-media-details");
    const search = document.getElementById("admin-media-search");
    const count = document.getElementById("admin-media-count");

    const libraryButton = document.getElementById("admin-media-show-library");
    const trashNavButton = document.getElementById("admin-media-show-trash");

    if (!folders || !grid || !details || !search || !count) return;


    let images = [];
    let currentFolder = "";
    let folderNames = [];

// ==========================================
// Caricamento nuove immagini
// ==========================================

const uploadButton = document.createElement("button");
uploadButton.type = "button";
uploadButton.className = "admin-media-upload";
uploadButton.textContent = "Carica immagine";

const fileInput = document.createElement("input");
fileInput.type = "file";
fileInput.accept = ".jpg,.jpeg,.png,.webp,.gif";
fileInput.hidden = true;

// Elimina eventuali controlli di caricamento precedenti
document.querySelectorAll(
    ".admin-media-upload, .admin-media-file-input"
).forEach(element => element.remove());

// Identifica il nuovo input
fileInput.className = "admin-media-file-input";

// Inserisce i controlli una sola volta
search.before(uploadButton, fileInput);

uploadButton.addEventListener("click", () => {
    if (!currentFolder) {
        alert("Seleziona prima una cartella di destinazione.");
        return;
    }

    fileInput.click();
});

fileInput.addEventListener("change", async () => {

    const file = fileInput.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append("image", file);
    formData.append("folder", currentFolder);

    uploadButton.disabled = true;
    uploadButton.textContent = "Caricamento...";

    try {
        const response = await fetch(
            window.APP_URL + "/FB-EDITOR/api/media-upload.php",
            {
                method: "POST",
                body: formData
            }
        );

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || "Errore caricamento");
        }

        alert("Immagine caricata correttamente.");

        // Ricarica la Libreria Media
        await loadMedia();

    } catch (error) {
        console.error("Errore upload:", error);
        alert(error.message);

    } finally {
        uploadButton.disabled = false;
        uploadButton.textContent = "Carica immagine";
        fileInput.value = "";
    }
});

    try {
        const response = await fetch(
            window.APP_URL + "/FB-EDITOR/api/media-list.php"
        );

        if (!response.ok) {
            throw new Error("HTTP " + response.status);
        }

        const data = await response.json();

        // L'API restituisce l'elenco delle immagini.
        images = Array.isArray(data)
            ? data
            : (Array.isArray(data.images) ? data.images : []);
        folderNames = Array.isArray(data.folders)
            ? data.folders
            : [...new Set(
                images.map(image => image.folder || "")
            )].sort();

    } catch (error) {
        console.error("Errore Media:", error);
        grid.textContent = "Errore durante il caricamento delle immagini.";
        return;
    }

    function renderFolders() {
        folders.replaceChildren();

        const addFolder = (name, value) => {
            const button = document.createElement("button");
            button.type = "button";
            button.textContent = name;
            button.className = "admin-media-folder";

            if (currentFolder === value) {
                button.classList.add("active");
            }

            button.addEventListener("click", () => {
                currentFolder = value;
                renderFolders();
                renderGrid();
                
            });

            folders.appendChild(button);
        };

        addFolder("Tutte le immagini", "");

        folderNames.forEach(folder => {
            if (folder) addFolder(folder, folder);
        });
    }

    function renderGrid() {
        grid.replaceChildren();

        const term = search.value.trim().toLowerCase();

        const filtered = images.filter(image => {
            const matchesFolder =
                !currentFolder || image.folder === currentFolder;

            const matchesSearch =
                (image.name || "").toLowerCase().includes(term);

            return matchesFolder && matchesSearch;
        });

        filtered.forEach(image => {
            const card = document.createElement("button");
            card.type = "button";
            card.className = "admin-media-item";

            const thumbnail = document.createElement("img");

            const thumbnailUrl = new URL(image.url, window.location.href);
            thumbnailUrl.searchParams.set("v", image.modified);
            thumbnail.src = thumbnailUrl.toString();
            thumbnail.alt = image.name || "";

            const name = document.createElement("span");
            name.textContent = image.name || "";

            card.append(thumbnail, name);

            card.addEventListener("click", () => {
                details.replaceChildren();

                const title = document.createElement("h3");
                title.textContent = image.name || "";

            const preview = document.createElement("img");

            const previewUrl = new URL(image.url, window.location.href);
            previewUrl.searchParams.set("v", image.modified);
            preview.src = previewUrl.toString();
            preview.alt = image.name || "";
            preview.style.width = "100%";

                // Informazioni immagine
const info = document.createElement("div");
info.className = "admin-media-info";

function addInfo(label, value) {
    const p = document.createElement("p");

    const strong = document.createElement("strong");
    strong.textContent = label + ": ";

    const span = document.createElement("span");
    span.textContent = value || "â€”";

    p.append(strong, span);
    info.appendChild(p);
}

addInfo("Cartella", image.folder || "Principale");
addInfo("Nome", image.name);
addInfo("Percorso", image.path);
addInfo("Tipo", image.type);

const size = Number(image.size);
addInfo(
    "Dimensione",
    Number.isFinite(size) && size >= 0
        ? (size / 1024).toFixed(1) + " KB"
        : "â€”"
);

let modified = "â€”";

if (image.modified) {
    const timestamp = Number(image.modified);

    const date = Number.isFinite(timestamp)
        ? new Date(timestamp * 1000)
        : new Date(image.modified);

    if (!Number.isNaN(date.getTime())) {
        modified = date.toLocaleString("it-IT");
    }
}

addInfo("Ultima modifica", modified);

// Pulsante copia percorso
const copyButton = document.createElement("button");
copyButton.type = "button";
copyButton.className = "admin-media-copy";
copyButton.textContent = "Copia percorso";

copyButton.addEventListener("click", async () => {
    try {
        await navigator.clipboard.writeText(image.path || "");
        copyButton.textContent = "Copiato!";
    } catch (error) {
        console.error("Errore copia percorso:", error);
        copyButton.textContent = "Copia non riuscita";
    }
});

// ==========================================
// Pulsante Sostituisci immagine
// ==========================================

const replaceButton = document.createElement("button");
replaceButton.type = "button";
replaceButton.className = "admin-media-replace";
replaceButton.textContent = "Sostituisci immagine";

const replaceInput = document.createElement("input");
replaceInput.type = "file";
replaceInput.hidden = true;

// Il formato deve rimanere invariato
const extension = (image.type || "").toLowerCase();

const acceptTypes = {
    jpg: ".jpg,.jpeg",
    jpeg: ".jpg,.jpeg",
    png: ".png",
    webp: ".webp",
    gif: ".gif"
};

replaceInput.accept = acceptTypes[extension] || "";

replaceButton.disabled = !acceptTypes[extension];

replaceButton.addEventListener("click", () => {
    replaceInput.click();
});

replaceInput.addEventListener("change", async () => {

    const file = replaceInput.files[0];
    if (!file) return;

    const confirmed = confirm(
        "Vuoi sostituire l'immagine " + image.name +
        "?\n\nLa modifica sarÃ  visibile anche nelle pagine del sito che la utilizzano."
    );

    if (!confirmed) {
        replaceInput.value = "";
        return;
    }

    const formData = new FormData();
    formData.append("path", image.path);
    formData.append("image", file);

    replaceButton.disabled = true;
    replaceButton.textContent = "Sostituzione...";

    try {
        const response = await fetch(
            window.APP_URL + "/FB-EDITOR/api/media-replace.php",
            {
                method: "POST",
                body: formData
            }
        );

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(
                result.message || "Errore durante la sostituzione"
            );
        }

        // Aggiorna subito l'anteprima evitando la cache
        const refreshedUrl = new URL(image.url, window.location.href);
        refreshedUrl.searchParams.set("v", Date.now());

        preview.src = refreshedUrl.toString();

        // Aggiorna anche la miniatura selezionata
        thumbnail.src = refreshedUrl.toString();

        alert("Immagine sostituita correttamente.");

        // Ricarica i dati della libreria
        await loadMedia();

    } catch (error) {
        console.error("Errore sostituzione:", error);
        alert(error.message);

    } finally {
        replaceButton.disabled = false;
        replaceButton.textContent = "Sostituisci immagine";
        replaceInput.value = "";
    }
});

// ==========================================
// Verifica utilizzo immagine (sola lettura)
// ==========================================
const usageButton = document.createElement("button");
usageButton.type = "button";
usageButton.className = "admin-media-usage";
usageButton.textContent = "Verifica utilizzo";

const usageResult = document.createElement("div");
usageResult.className = "admin-media-usage-result";
usageResult.setAttribute("role", "status");
usageResult.setAttribute("aria-live", "polite");

usageButton.addEventListener("click", async () => {
    usageButton.disabled = true;
    usageButton.textContent = "Verifica in corso...";
    usageResult.replaceChildren();

    try {
        const url = new URL(
            window.APP_URL + "/FB-EDITOR/api/media-usage.php",
            window.location.href
        );
        url.searchParams.set("path", image.path);

        const response = await fetch(url.toString(), { cache: "no-store" });
        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.error || "Verifica non riuscita");
        }

        const summary = document.createElement("p");
        if (!result.complete || (Array.isArray(result.errors) && result.errors.length)) {
            summary.textContent = "Verifica incompleta: eliminazione non sicura.";
        } else if (result.used) {
            summary.textContent = "Immagine utilizzata: non eliminare.";
        } else {
            summary.textContent = "Nessun riferimento trovato nei JSON controllati. Non Ã¨ una garanzia di inutilizzo.";
        }
        usageResult.appendChild(summary);

        if (Array.isArray(result.references) && result.references.length) {
            const list = document.createElement("ul");
            result.references.forEach(ref => {
                const item = document.createElement("li");
                item.textContent = [ref.file, ref.type, ref.field, ref.folder]
                    .filter(Boolean).join(" â€” ");
                list.appendChild(item);
            });
            usageResult.appendChild(list);
        }

        if (Array.isArray(result.errors) && result.errors.length) {
            const errorList = document.createElement("ul");
            result.errors.forEach(error => {
                const item = document.createElement("li");
                item.textContent = typeof error === "string"
                    ? error : JSON.stringify(error);
                errorList.appendChild(item);
            });
            usageResult.appendChild(errorList);
        }
    } catch (error) {
        console.error("Errore verifica utilizzo:", error);
        usageResult.textContent = "Impossibile verificare l'utilizzo: " + error.message;
    } finally {
        usageButton.disabled = false;
        usageButton.textContent = "Verifica utilizzo";
    }
});


const trashButton = document.createElement("button");
trashButton.type = "button";
trashButton.className = "admin-media-trash";
trashButton.textContent = "Sposta nel cestino";

trashButton.addEventListener("click", async () => {
    const confirmed = confirm(
        "Vuoi spostare nel cestino l'immagine:\n" +
        image.name +
        "\n\nIl server verificherà nuovamente se è utilizzata."
    );

    if (!confirmed) return;

    trashButton.disabled = true;
    trashButton.textContent = "Verifica in corso...";


    try {

        // Esegue la richiesta di spostamento.
        // La conferma aggiuntiva è inviata soltanto se necessaria.
        async function requestTrash(force = false) {

            const response = await fetch(
                window.APP_URL + "/FB-EDITOR/api/media-trash.php",
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: new URLSearchParams({
                        path: image.path,
                        ...(force ? { force: "CONFIRM_USED" } : {})
                    })
                }
            );

            const raw = await response.text();
            let result;

            try {
                result = JSON.parse(raw);
            } catch {
                throw new Error(
                    "Risposta del server non valida. Controllare media-trash.php."
                );
            }

            return { response, result };
        }

        // Prima richiesta: verifica normale
        let { response, result } = await requestTrash();

        // Il server ha rilevato un'immagine utilizzata
        if (result.requires_confirmation === true) {

            const references = [];

            // Riferimenti rilevati nei file pubblicati
            if (Array.isArray(result.published_references)) {
                references.push(...result.published_references);
            }

            const referenceText = references.length
                ? "\n\nRiferimenti rilevati:\n" +
                  references.join("\n")
                : "";

            const confirmed = confirm(
                "ATTENZIONE: IMMAGINE UTILIZZATA\n\n" +
                "L'immagine:\n" +
                image.path +
                "\n\nrisulta utilizzata nel sito." +
                referenceText +
                "\n\nSpostandola nel cestino potrebbe non essere " +
                "più visibile nelle pagine o nelle gallery." +
                "\n\nPotrai comunque ripristinarla." +
                "\n\nVuoi procedere?"
            );

            if (!confirmed) {
                return;
            }

            // Seconda richiesta: conferma esplicita
            ({ response, result } = await requestTrash(true));
        }

        if (!response.ok || !result.success) {
            throw new Error(
                result.error || "Spostamento non consentito"
            );
        }

alert("Immagine spostata nel cestino.");

// Pulisce il pannello dei dettagli dell'immagine eliminata
details.replaceChildren();
details.textContent = "Seleziona un'immagine";

// Aggiorna la Libreria Media
await loadMedia();
    }

    catch (error) {
        alert("Spostamento non eseguito: " + error.message);
        console.error("Errore cestino media:", error);

    } 
    finally {
        trashButton.disabled = false;
        trashButton.textContent = "Sposta nel cestino";
    }
});

details.append(
    title,
    preview,
    info,
    copyButton,
    replaceButton,
    replaceInput,
    usageButton,
    usageResult,
    trashButton
);

            });

            grid.appendChild(card);
        });

        count.textContent = filtered.length + " immagini";
    }

    search.addEventListener("input", renderGrid);

    renderFolders();
    renderGrid();
    // ==========================================
    // Navigazione Libreria / Cestino Media
    // ==========================================

    async function showTrash() {
        libraryButton.classList.remove("active");
        trashNavButton.classList.add("active");

        folders.replaceChildren();
        grid.replaceChildren();
        details.textContent = "Seleziona un'immagine dal cestino";
        count.textContent = "";
        search.value = "";
        search.hidden = true;
        uploadButton.hidden = true;
        fileInput.hidden = true;

        try {
            const response = await fetch(
                window.APP_URL + "/FB-EDITOR/api/media-trash-list.php",
                { cache: "no-store" }
            );

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.error || "Errore lettura cestino");
            }

            const items = Array.isArray(result.images)
                ? result.images
                : [];

            count.textContent = items.length + " immagini nel cestino";

            if (items.length === 0) {
                grid.textContent = "Il cestino è vuoto.";
                return;
            }

            items.forEach(item => {
                const card = document.createElement("button");
                card.type = "button";
                card.className = "admin-media-item";
                card.textContent = item.name;

                card.addEventListener("click", () => {
                    details.replaceChildren();

                    const title = document.createElement("h3");
                    title.textContent = item.name;

                    const info = document.createElement("div");
                    info.className = "admin-media-info";

                    const path = document.createElement("p");
                    path.textContent =
                        "Percorso originale: " + item.original_path;

                    const date = document.createElement("p");
                    const parsedDate = new Date(item.deleted_at);
                    date.textContent = "Spostata nel cestino: " +
                        (Number.isNaN(parsedDate.getTime())
                            ? "Data non disponibile"
                            : parsedDate.toLocaleString("it-IT"));

                    const restoreButton = document.createElement("button");
                    restoreButton.type = "button";
                    restoreButton.className = "admin-media-restore";
                    restoreButton.textContent = "Ripristina immagine";

                    restoreButton.addEventListener("click", async () => {
                        if (!confirm(
                            "Ripristinare l'immagine " + item.name + "?"
                        )) return;

                        restoreButton.disabled = true;
                        restoreButton.textContent = "Ripristino...";

                        try {
                            const response = await fetch(
                                window.APP_URL + "/FB-EDITOR/api/media-restore.php",
                                {
                                    method: "POST",
                                    headers: {
                                        "Content-Type":
                                            "application/x-www-form-urlencoded"
                                    },
                                    body: new URLSearchParams({
                                        id: item.id
                                    })
                                }
                            );

                            const result = await response.json();

                            if (!response.ok || !result.success) {
                                throw new Error(
                                    result.error || "Ripristino non riuscito"
                                );
                            }

                            alert("Immagine ripristinata correttamente.");
                            await showTrash();

                        } catch (error) {
                            alert("Errore ripristino: " + error.message);
                        } finally {
                            restoreButton.disabled = false;
                            restoreButton.textContent = "Ripristina immagine";
                        }
                    });

                    
                    // ==========================================
                    // Eliminazione definitiva dal Cestino Media
                    // ==========================================

                    const deleteButton = document.createElement("button");
                    deleteButton.type = "button";
                    deleteButton.className = "admin-media-trash";
                    deleteButton.textContent = "Elimina definitivamente";

                    deleteButton.addEventListener("click", async () => {

                        const confirmed = confirm(
                            "ATTENZIONE!\n\n" +
                            "Vuoi eliminare DEFINITIVAMENTE l'immagine:\n" +
                            item.name +
                            "\n\nQuesta operazione è irreversibile."
                        );

                        if (!confirmed) return;

                        deleteButton.disabled = true;
                        deleteButton.textContent = "Eliminazione...";

                        try {
                            const response = await fetch(
                                window.APP_URL + "/FB-EDITOR/api/media-trash-delete.php",
                                {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/x-www-form-urlencoded"
                                    },
                                    body: new URLSearchParams({
                                        id: item.id,
                                        confirm: "DELETE"
                                    })
                                }
                            );

                            const result = await response.json();

                            if (!response.ok || !result.success) {
                                throw new Error(
                                    result.error || "Eliminazione non riuscita"
                                );
                            }

                            alert("Immagine eliminata definitivamente.");
                            await showTrash();

                        } catch (error) {
                            console.error("Errore eliminazione definitiva:", error);
                            alert("Eliminazione non eseguita: " + error.message);

                        } finally {
                            deleteButton.disabled = false;
                            deleteButton.textContent = "Elimina definitivamente";
                        }
                    });

                    info.append(path, date);

                    details.append(
                        title,
                        info,
                        restoreButton,
                        deleteButton
                    );

                });

                grid.appendChild(card);
            });

        } catch (error) {
            console.error("Errore lettura cestino:", error);
            grid.textContent = "Impossibile caricare il cestino: " +
                error.message;
        }
    }

    if (libraryButton && trashNavButton) {
        libraryButton.onclick = () => {
            libraryButton.classList.add("active");
            trashNavButton.classList.remove("active");
            search.hidden = false;
            uploadButton.hidden = false;
            loadMedia();
        };

        trashNavButton.onclick = showTrash;
    }
}
