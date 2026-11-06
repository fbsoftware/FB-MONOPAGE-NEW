//===========================================
// CONFIGURAZIONE SITO
//===========================================

async function loadConfiguration() {

    try {

        const response = await fetch(
            window.APP_URL +
            "/FB-EDITOR/editor/site-config.json?t=" +
            Date.now()
        );

        if (!response.ok) {
            throw new Error(
                "Errore caricamento configurazione"
            );
        }

        const config = await response.json();

        console.log(
            "CONFIGURAZIONE CARICATA:",
            config
        );

        //=================================
        // COLORI
        //=================================

        document.querySelector("#config-color-primary").value =
            config.colors?.primary ?? "#3366ff";

        document.querySelector("#config-color-secondary").value =
            config.colors?.secondary ?? "#ff6633";

        document.querySelector("#config-color-accent").value =
            config.colors?.accent ?? "#ffa500";

        document.querySelector("#config-color-text").value =
            config.colors?.text ?? "#222222";

        document.querySelector("#config-color-bg").value =
            config.colors?.bg ?? "#ffffff";


        //=================================
        // FONT HEADING
        //=================================

        document.querySelector("#config-heading-family").value =
            config.typography?.heading?.fontFamily ?? "Inter";

        document.querySelector("#config-heading-weight").value =
            config.typography?.heading?.weight ?? 400;


        //=================================
        // FONT BODY
        //=================================

        document.querySelector("#config-body-family").value =
            config.typography?.body?.fontFamily ?? "Inter";

        document.querySelector("#config-body-weight").value =
            config.typography?.body?.weight ?? 400;


        //=================================
        // DIMENSIONI
        //=================================

        document.querySelector("#config-font-h1").value =
            config.typography?.sizes?.h1 ?? 48;

        document.querySelector("#config-font-h2").value =
            config.typography?.sizes?.h2 ?? 36;

        document.querySelector("#config-font-h3").value =
            config.typography?.sizes?.h3 ?? 28;

        document.querySelector("#config-font-body").value =
            config.typography?.sizes?.body ?? 16;

        document.querySelector("#config-font-small").value =
            config.typography?.sizes?.small ?? 14;

        // Bind salvataggio
        bindConfigurationSave();

    } catch(error) {

        console.error(
            "Errore caricamento configurazione:",
            error
        );

        const status =
            document.querySelector("#siteConfigStatus");

        if (status) {
            status.textContent =
                "Errore caricamento configurazione";
        }
    }
}


//===========================================
// SALVA CONFIGURAZIONE
//===========================================

function bindConfigurationSave() {

    const button =
        document.querySelector("#saveSiteConfig");

    if (!button) return;

    button.onclick = async function() {

        const config = {

            colors: {

                primary:
                    document.querySelector(
                        "#config-color-primary"
                    ).value,

                secondary:
                    document.querySelector(
                        "#config-color-secondary"
                    ).value,

                accent:
                    document.querySelector(
                        "#config-color-accent"
                    ).value,

                text:
                    document.querySelector(
                        "#config-color-text"
                    ).value,

                bg:
                    document.querySelector(
                        "#config-color-bg"
                    ).value
            },

            typography: {

                heading: {

                    fontFamily:
                        document.querySelector(
                            "#config-heading-family"
                        ).value,

                    weight:
                        parseInt(
                            document.querySelector(
                                "#config-heading-weight"
                            ).value,
                            10
                        )
                },

                body: {

                    fontFamily:
                        document.querySelector(
                            "#config-body-family"
                        ).value,

                    weight:
                        parseInt(
                            document.querySelector(
                                "#config-body-weight"
                            ).value,
                            10
                        )
                },

                sizes: {

                    h1:
                        parseInt(
                            document.querySelector(
                                "#config-font-h1"
                            ).value,
                            10
                        ),

                    h2:
                        parseInt(
                            document.querySelector(
                                "#config-font-h2"
                            ).value,
                            10
                        ),

                    h3:
                        parseInt(
                            document.querySelector(
                                "#config-font-h3"
                            ).value,
                            10
                        ),

                    body:
                        parseInt(
                            document.querySelector(
                                "#config-font-body"
                            ).value,
                            10
                        ),

                    small:
                        parseInt(
                            document.querySelector(
                                "#config-font-small"
                            ).value,
                            10
                        )
                }
            }
        };

        console.log(
            "CONFIGURAZIONE DA SALVARE:",
            config
        );

        try {

            const response = await fetch(
                window.APP_URL +
                "/FB-EDITOR/editor/save-site-config.php",
                {
                    method: "POST",

                    headers: {
                        "Content-Type":
                            "application/json"
                    },

                    body: JSON.stringify(config)
                }
            );

            const result =
                await response.json();

            console.log(
                "RISULTATO SALVATAGGIO:",
                result
            );

            if (!result.ok) {

                throw new Error(
                    "Salvataggio non riuscito"
                );
            }

            const status =
                document.querySelector(
                    "#siteConfigStatus"
                );

            if (status) {
                status.textContent =
                    "✔ Salvato";
            }

            alert("Configurazione salvata!");

        } catch(error) {

            console.error(
                "Errore salvataggio configurazione:",
                error
            );

            alert(
                "Errore salvataggio configurazione: " +
                error.message
            );
        }
    };
}