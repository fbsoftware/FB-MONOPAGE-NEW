
//============================================
//  🧱 2.HTML widgets
//============================================
editor.renderWidgetHTML = function(widget){
    const def = editor.widgets[widget.type];

    if (!def || !def.render) return "";

    return def.render(widget); // già pulito 👍
};

//============================================
//  🧱 3. render colonna
//============================================
editor.renderColumnHTML = function(column){
    return `
        <div class="col">
            ${column.widgets.map(w => editor.renderWidgetHTML(w)).join("")}
        </div>
    `;
};

//============================================
//  🧱 4. render sezione
//============================================
editor.renderSectionHTML = function(section){
    return `
        <section class="section">
            ${section.columns.map(c => editor.renderColumnHTML(c)).join("")}
        </section>
    `;
};

//============================================
//  🧱 5. render pagina completa
//============================================
editor.renderPageHTML = function(data){
    return `
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Pagina</title>

    <style>
        body { font-family: sans-serif; }

        :root {
            --color-primary: #3366ff;
            --color-secondary: #ff6633;
            --color-accent: #ffa500;
            --color-text: #222;
            --color-bg: #fff;
        }
        .section {
            display: flex;
            gap: 20px;
            padding: 20px; 
        }
        .col {
            flex: 1;
            margin-bottom: 20px;
            width: 100%;
        }
    </style>
</head>
<body>

    ${data.sections.map(s => editor.renderSectionHTML(s)).join("")}

</body>
</html>
`;
};

//============================================
//  💾 6. esportare file index.html
//============================================
function downloadHTML(html){
    const blob = new Blob([html], { type: "text/html" });
    const a = document.createElement("a");

    a.href = URL.createObjectURL(blob);
    a.download = "index.html";
    a.click();
}
