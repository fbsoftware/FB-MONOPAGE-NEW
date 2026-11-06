
// ==========================================
// FB-EDITOR - MEDIA MANAGER
// Prima versione: visualizzazione e selezione
// ==========================================

window.FBMediaManager = {

    images: [],
    selected: null,
    folder: "all",
    onSelect: null,

    open: async function(options = {}) {

        this.onSelect = options.onSelect || null;
        this.selected = null;
        this.folder = "all";

        $("#fb-media-search").val("");
        $("#fb-media-select")
            .prop("disabled", true)
            .toggle(!!this.onSelect);

        $("#fb-media-manager").addClass("is-open");

        await this.load();
    },

    close: function() {
        $("#fb-media-manager").removeClass("is-open");
        this.onSelect = null;
    },

    load: async function() {

        const $grid = $("#fb-media-grid");
        $grid.empty().text("Caricamento immagini...");

        try {

            const response = await fetch(
                window.FB_APP.appUrl +
                "/FB-EDITOR/api/media-list.php",
                { cache: "no-store" }
            );

            if (!response.ok) {
                throw new Error("HTTP " + response.status);
            }

            const data = await response.json();

            if (!data.success || !Array.isArray(data.images)) {
                throw new Error("Risposta API non valida");
            }

            this.images = data.images;

            this.renderFolders();
            this.renderGrid();
            this.renderDetails();

        } catch (error) {

            console.error("Media Manager:", error);

            $grid.empty().text(
                "Errore nel caricamento delle immagini."
            );
        }
    },

    renderFolders: function() {

        const folders = [
            ...new Set(
                this.images.map(image => image.folder)
            )
        ].sort();

        const $container = $("#fb-media-folders").empty();

        this.addFolderButton($container, "all", "Tutti i media");

        folders.forEach(folder => {
            this.addFolderButton(
                $container,
                folder,
                folder
            );
        });
    },

    addFolderButton: function($container, folder, label) {

        const $button = $("<button>", {
            type: "button",
            class: "fb-media-folder",
            text: label
        });

        if (folder === this.folder) {
            $button.addClass("active");
        }

        $button.on("click", () => {

            this.folder = folder;

            $("#fb-media-folders .fb-media-folder")
                .removeClass("active");

            $button.addClass("active");

            this.renderGrid();
        });

        $container.append($button);
    },

    renderGrid: function() {

        const search = String(
            $("#fb-media-search").val() || ""
        ).toLowerCase().trim();

        const images = this.images.filter(image => {

            const matchesFolder =
                this.folder === "all" ||
                image.folder === this.folder;

            const matchesSearch =
                image.name.toLowerCase().includes(search);

            return matchesFolder && matchesSearch;
        });

        const $grid = $("#fb-media-grid").empty();

        $("#fb-media-count").text(
            images.length + " immagini visualizzate"
        );

        if (!images.length) {
            $grid.text("Nessuna immagine trovata.");
            return;
        }

        images.forEach(image => {

            const $item = $("<button>", {
                type: "button",
                class: "fb-media-item",
                title: image.name
            });

            if (
                this.selected &&
                this.selected.path === image.path
            ) {
                $item.addClass("selected");
            }

            $("<img>", {
                src: image.url,
                alt: image.name,
                loading: "lazy"
            }).appendTo($item);

            $("<span>")
                .text(image.name)
                .appendTo($item);

            $item.on("click", () => {

                this.selected = image;

                $("#fb-media-select")
                    .prop("disabled", !this.onSelect);

                this.renderGrid();
                this.renderDetails();
            });

            $grid.append($item);
        });
    },

    renderDetails: function() {

        const $details = $("#fb-media-details").empty();

        if (!this.selected) {
            $details.text("Seleziona un'immagine.");
            return;
        }

        const image = this.selected;

        $("<img>", {
            src: image.url,
            alt: image.name
        }).appendTo($details);

        const fields = {
            "Nome": image.name,
            "Cartella": image.folder,
            "Formato": image.type,
            "Dimensione": Math.round(image.size / 1024) + " KB",
            "Percorso": image.path
        };

        Object.entries(fields).forEach(([label, value]) => {

            const $p = $("<p>");

            $("<strong>")
                .text(label + ": ")
                .appendTo($p);

            $p.append(document.createTextNode(String(value)));

            $details.append($p);
        });
    }
};

// ==========================================
// Eventi interfaccia
// ==========================================

$(document).on("click", "#open-media-manager", function() {
    FBMediaManager.open();
});

$(document).on("click", "#fb-media-close", function() {
    FBMediaManager.close();
});

$(document).on("click", "#fb-media-manager", function(e) {
    if (e.target === this) {
        FBMediaManager.close();
    }
});

$(document).on("input", "#fb-media-search", function() {
    FBMediaManager.renderGrid();
});

$(document).on("click", "#fb-media-select", function() {

    if (
        FBMediaManager.selected &&
        typeof FBMediaManager.onSelect === "function"
    ) {
        FBMediaManager.onSelect(FBMediaManager.selected);
        FBMediaManager.close();
    }
});

// ==========================================
// MEDIA MANAGER - COLLEGAMENTO INSPECTOR
// ==========================================

$(document).on("click", ".open-inspector-media", function(e) {

    e.preventDefault();
    e.stopPropagation();

    const field = $(this).attr("data-media-field");

    const selectedType = editor.state.selectedType;
    const selectedId = editor.state.selectedId;

    FBMediaManager.open({

        onSelect: function(image) {

            const value = image.path;

            if (selectedType === "widget") {

                const widget = editor.findWidgetById(selectedId);

                if (!widget) return;

                widget.props = widget.props || {};
                widget.props[field] = value;

                editor.state.isDirty = true;

                editor.render();

                editor.openWidgetInspector(selectedId);

           
        } else if (selectedType === "section") {

            const section = editor.findSectionById(selectedId);

            if (!section) return;

            section[field] = value;

            editor.state.isDirty = true;

            editor.render();
            editor.openSectionInspector(selectedId);

                    } else if (selectedType === "column") {

                        const column = editor.findColumnById(selectedId);

                        if (!column) return;

                        column[field] = value;

                        editor.state.isDirty = true;

                        editor.render();
                        editor.openColumnInspector(selectedId);
                    }
                }
            });
        });

// ==========================================
// RIMUOVI IMMAGINE DALL'INSPECTOR
// ==========================================
$(document).off("click", ".remove-inspector-image");

$(document).on("click", ".remove-inspector-image", function(e) {

    e.preventDefault();
    e.stopPropagation();

    const field = $(this).attr("data-media-field");
    const selectedId = editor.state.selectedId;

    // Cerca prima il widget selezionato
    if (editor.state.selectedType === "widget") {

        const widget = editor.findWidgetById(selectedId);
        if (!widget) return;

        widget.props = widget.props || {};
        widget.props[field] = "";

        editor.state.isDirty = true;
        editor.render();
        editor.openWidgetInspector(selectedId);

        return;
    }

    // Gestione sfondo della sezione
    if (editor.state.selectedType === "section") {

        const section = editor.findSectionById(selectedId);
        if (!section) return;

        section.backgroundImage = "";

        editor.state.isDirty = true;
        editor.render();
        editor.openSectionInspector(selectedId);

        return;
    }

    console.warn(
        "Rimozione immagine: tipo selezione non gestito",
        editor.state.selectedType
    );
});
