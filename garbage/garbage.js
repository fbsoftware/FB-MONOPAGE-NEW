//******************************************** */
 // Render singolo widget in HTML pubblico
//*/****************************************** */
function renderWidgetHTML(array $widget): string
{
    $type  = $widget['type'] ?? '';
    $props = $widget['props'] ?? [];

    switch ($type) {
        case 'text':
            $text  = $props['text'] ?? '';
            $align = $props['align'] ?? 'left';
            $color = $props['color'] ?? 'inherit';

            return '<div class="widget-text" style="text-align:' . h($align) . ';color:' . h($color) . ';">'
                . nl2br(h($text))
                . '</div>';

        case 'textarea':
            $text  = $props['text'] ?? '';
            $align = $props['align'] ?? 'left';
            $color = $props['color'] ?? 'inherit';

            return '<div class="widget-text" style="text-align:' . h($align) . ';color:' . h($color) . ';">'
                . nl2br(h($text))
                . '</div>';

        case 'header':
            $text  = $props['text'] ?? '';
            $level = $props['level'] ?? 'h2';
            $align = $props['align'] ?? 'left';
            $color = $props['color'] ?? 'inherit';

            $allowed = ['h1','h2','h3','h4','h5','h6'];
            if (!in_array($level, $allowed, true)) {
                $level = 'h2';
            }

            return '<div class="widget-header" style="text-align:' . h($align) . ';">'
                . '<' . $level . ' style="color:' . h($color) . ';">' . h($text) . '</' . $level . '>'
                . '</div>';

        case 'button':
            $text  = $props['text'] ?? 'Bottone';
            $url   = $props['url'] ?? '#';
            $align = $props['align'] ?? 'left';

            return '<div class="widget-button" style="text-align:' . h($align) . ';">'
                . '<a href="' . h($url) . '">' . h($text) . '</a>'
                . '</div>';

        case 'image':
            $src = $props['src'] ?? '';
            $alt = $props['alt'] ?? '';

            return '<div class="widget-image">'
                . '<img src="' . h($src) . '" alt="' . h($alt) . '">'
                . '</div>';

        case 'spacer':
            $height = $props['height'] ?? '40px';

            return '<div class="widget-spacer" style="height:' . h($height) . ';"></div>';

        default:
            return '<div class="widget-unknown">Widget non supportato: ' . h($type) . '</div>';
    }
}

/**
 * Render colonna in HTML pubblico
 */
function renderColumnHTML(array $column): string
{
    $width = $column['width'] ?? 100;
    $widgets = $column['widgets'] ?? [];

    $html = '<div class="page-column" style="flex-basis:' . h($width) . '%;">';

    foreach ($widgets as $widget) {
        $html .= renderWidgetHTML($widget);
    }

    $html .= '</div>';

    return $html;
}

/**
 * Render sezione in HTML pubblico
 */
function renderSectionHTML(array $section): string
{
    $background = $section['background'] ?? 'transparent';
    $padding    = isset($section['padding']) ? ((int)$section['padding'] . 'px') : '20px';
    $margin     = isset($section['margin']) ? ((int)$section['margin'] . 'px') : '0px';
    $columns    = $section['columns'] ?? [];

    $html = '<section class="page-section" style="background:' . h($background) . ';padding:' . h($padding) . ';margin:' . h($margin) . ';">';
    $html .= '<div class="page-columns">';

    foreach ($columns as $column) {
        $html .= renderColumnHTML($column);
    }

    $html .= '</div>';
    $html .= '</section>';

    return $html;
}

/*============================
 * Render pagina completa
 =============================*/
function renderPageHTML(array $data): string
{
    $sections = $data['sections'] ?? [];

    $body = '';
    foreach ($sections as $section) {
        $body .= renderSectionHTML($section) . "\n";
    }

    return '<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . h($data['meta']['page'] ?? 'Pagina') . ' - RenderPageHTML</title>
    <link rel="stylesheet" href="/assets/css/site.css">
    <style>
        .page-section{
            margin-bottom:20px;
            width:1240px;
        }
        .page-columns{
            display:flex;
            gap:10px;
            align-items:stretch;
        }
        .page-column{
            box-sizing:border-box;
        }
        .page-column img{
            max-width:100%;
            height:auto;
            display:block;
        }
        .widget-button a{
            display:inline-block;
            text-decoration:none;
        }
    </style>
</head>
<body>
' . $body . '
</body>
</html>';
}

$htmlPath = $pagesDir . '/' . $page . '.html';
$html = renderPageHTML($data);

if (file_put_contents($htmlPath, $html) === false) {
    echo json_encode([
        'success' => false,
        'error' => 'JSON salvato ma errore scrittura HTML'
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'json' => 'data/' . $page . '.json',
    'html' => 'pages/' . $page . '.html'
]);

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
          <div class="section-inner" style="max-width:1240px; margin:0 auto;">
            ${section.columns.map(c => editor.renderColumnHTML(c)).join("")}
        </section>
    `;
};

//============================================
//  🧱 5. render pagina completa
//============================================
editor.renderPageHTML = function(data){

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
        }
    </style>
</head>
<body> 
    return `
    ${data.sections.map(s => editor.renderSectionHTML(s)).join("")}
`;

</body>
</html>

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