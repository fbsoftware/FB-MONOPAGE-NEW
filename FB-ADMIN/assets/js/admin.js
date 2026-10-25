document.addEventListener("DOMContentLoaded", function () {

    document
        .querySelectorAll("[data-view]")
        .forEach(function (item) {

            item.addEventListener("click", async function (event) {

                event.preventDefault();

                const view = item.dataset.view;

                const response = await fetch(
                    window.APP_URL +
                    "/FB-ADMIN/views/" +
                    view +
                    ".php"
                );

                const html = await response.text();

                document.querySelector("#admin-content").innerHTML = html;
                if (view === "pages") {
                    loadPages();
                }
                if (view === "templates") {
                    loadTemplates();
                }

                if (view === "slides") {
                    loadSlides();
                }
                if (view === "menu") {
                    loadMenu();
                }
                if (view === "global") {
                    loadGlobals();
                }
            });

        });

});