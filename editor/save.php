<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'error' => 'Metodo non consentito'
    ]);
    exit;
}

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    echo json_encode([
        'success' => false,
        'error' => 'JSON non valido'
    ]);
    exit;
}

$page = $data['meta']['page'] ?? null;

if (!$page) {
    echo json_encode([
        'success' => false,
        'error' => 'Nome pagina mancante'
    ]);
    exit;
}

$page = preg_replace('/[^a-zA-Z0-9\-_]/', '', $page);

$rootDir  = dirname(__DIR__);
$dataDir  = $rootDir . '/data';
$pagesDir = $rootDir . '/pages';

if (!is_dir($dataDir)) {
    mkdir($dataDir, 0777, true);
}

if (!is_dir($pagesDir)) {
    mkdir($pagesDir, 0777, true);
}

$data['meta']['updated_at'] = date('c');

$jsonPath = $dataDir . '/' . $page . '.json';
$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

if (file_put_contents($jsonPath, $json) === false) {
    echo json_encode([
        'success' => false,
        'error' => 'Errore scrittura JSON'
    ]);
    exit;
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



/** * Escape HTML */
function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function getYoutubeVideoId(string $url): string
{
    if ($url === '') {
        return '';
    }

    $patterns = [
        '/youtube\.com\/watch\?v=([^&]+)/',
        '/youtu\.be\/([^?&]+)/',
        '/youtube\.com\/embed\/([^?&]+)/',
        '/youtube\.com\/shorts\/([^?&]+)/'
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1] ?? '';
        }
    }

    return '';
}

function getVideoAspectRatioPadding(string $ratio): string
{
    switch ($ratio) {
        case '4:3':
            return '75%';
        case '1:1':
            return '100%';
        case '16:9':
        default:
            return '56.25%';
    }
}

//******************************************** */
 // Render singolo widget in HTML pubblico
//*/****************************************** */
function renderWidgetHTML(array $widget): string
{
    $type  = $widget['type'] ?? '';
    $props = $widget['props'] ?? [];
//error_log('Rendering widget: ' . $type); 
     switch ($type) {
        case 'text':
            $text  = $props['text'] ?? '';
            $align = $props['align'] ?? 'left';
            $color = $props['color'] ?? 'inherit';
            $padding = isset($props['padding']) ? ((int)$props['padding'] . 'px') : '0px';
            $margin = isset($props['margin']) ? ((int)$props['margin'] . 'px') : '0px';
            $customCss = $props['customCss'] ?? '';

            return '<div class="widget-text" 
                style="text-align:' . h($align) . '; 
                color:' . h($color) . ';
                padding:' . h($padding) . ';
                margin:' . h($margin) . ';
                 ' . h($customCss) . '">'
                . nl2br(h($text))
                . '</div>';

        case 'textarea':
            $text  = $props['text'] ?? '';
            $align = $props['align'] ?? 'left';
            $color = $props['color'] ?? 'inherit';
            $customCss = $props['customCss'] ?? '';

            return '<div class="widget-text" 
                        style="text-align:' . h($align) . ';
                        color:' . h($color) . ';
                         ' . h($customCss) . '">'
                . nl2br(h($text))
                . '</div>';

        case 'header':
            $text  = $props['text'] ?? '';
            $level = $props['level'] ?? 'h2';
            $align = $props['align'] ?? 'left';
            $color = $props['color'] ?? 'primary';
            $padding = isset($props['padding']) ? ((int)$props['padding'] . 'px') : '0px';
            $margin = isset($props['margin']) ? ((int)$props['margin'] . 'px') : '0px';
            $customCss = $props['customCss'] ?? '';

            $allowed = ['h1','h2','h3','h4','h5','h6'];
            if (!in_array($level, $allowed, true)) {
                $level = 'h2';
            }

            return '<div class="widget-header" style="text-align:' . h($align) . ';
                padding:' . h($padding) . ';
                margin:' . h($margin) . ';
                 ' . h($customCss) . '">'
                . '<' . $level . ' style="color:' . h($color) . ';">' . h($text) . '</' . $level . '>'
                . '</div>';

        case 'button':
            $text  = $props['text'] ?? 'Bottone';
            $url   = $props['url'] ?? '#';
            $align = $props['align'] ?? 'left';
            $color = $props['color'] ?? 'accent';
            $sfondo = $props['sfondo'] ?? '';
            $bordo = $props['bordo'] ?? 25;
            $padd = $props['padd'] ?? 20;
            $padding = $props['padding'] ?? 20;
            $fontSize = $props['fontSize'] ?? 22;
            $fontWeight = $props['fontWeight'] ?? 600;
            $customCss = $props['customCss'] ?? '';
            $buttonCss = $props['buttonCss'] ?? '';

            return '<div class="widget-button" 
                            style="text-align:' . h($align) . '; 
                            padding:' . h($padding) . 'px;  
                            ' . h($customCss) . '">
                <a href="' . h($url) . '" style="
                    display:inline-block;
                    width:auto;
                    background-color:' . h($sfondo) . ';
                    border-radius:' . h($bordo) . 'px;
                    padding:' . h($padd) . 'px;
                    text-decoration:none;
                    font-size:' . h($fontSize) . 'px;
                    font-weight:' . h($fontWeight) . ';
                    color:' . h($color) . ';
                    cursor:pointer;
                    ' . h($buttonCss) . '
                ">
                    ' . h($text) . '
                </a>
            </div>';

        case 'image':
            $src = $props['src'] ?? '';
            $alt = $props['alt'] ?? '';
            $align = $props['align'] ?? 'left';
            $width = isset($props['width']) ? ((int)$props['width'] . 'px') : 'auto';
            $customCss = $props['customCss'] ?? '';
            $imgCss = $props['imgCss'] ?? '';
            return '<div class="widget-image" 
                        style="justify-content:' . h($align) . ';
                        align-items:' . h($align) . ';
                        ' . h($customCss) . '">'
                . '<img src="' . h($src) . '" 
                        alt="' . h($alt) . '" 
                        style="width:' . h($width) . ';
                        ' . h($imgCss) . '">'
                . '</div>';

        case 'spacer':
            $height = isset($props['height']) ? ((int)$props['height'] . 'px') : '40px';
            $customCss = $props['customCss'] ?? '';

            return '<div class="widget-spacer" 
                        style="height:' . h($height) . ';
                        ' . h($customCss) . '">
                    </div>';

        case 'icon':
            $align = $props['align'] ?? 'center';
            $size = $props['size'] ?? 48;
            $color = $props['color'] ?? 'var(--color-primary)';
            $name = $props['name'] ?? 'home';
            $padding = isset($props['padding']) ? ((int)$props['padding'] . 'px') : '0px';
            $margin = isset($props['margin']) ? ((int)$props['margin'] . 'px') : '0px';
            $customCss = $props['customCss'] ?? '';
            $iconCss = $props['iconCss'] ?? '';

             return '<div class="widget-icon" 
                style="display:flex;
                    justify-content:' . h($align) . ';
                    align-items:' . h($align) . ';
                    padding:' . h($padding) . ';
                    margin:' . h($margin) . ';
                    ' . h($customCss) . '">
                <span class="material-symbols-outlined" style="
                    font-size:' . h($size) . 'px!important;
                    color:' . h($color) . ';
                    padding:' . h($padding) . ';
                    margin:' . h($margin) . ';
                    ' . h($iconCss) . '">
                    ' . h($name) . '
                </span>
            </div>';

        case 'video':

            $url = $props['url'] ?? '';
            $aspectRatio = $props['aspectRatio'] ?? '16:9';
            $align = $props['align'] ?? 'center';
            $padding = isset($props['padding']) ? (int)$props['padding'] : 0;
            $margin = isset($props['margin']) ? (int)$props['margin'] : 0;
            $customCss = $props['customCss'] ?? '';
            $videoCss = $props['videoCss'] ?? '';

            $videoId = getYoutubeVideoId($url);

            if ($videoId === '') {
                return '';
            }

            $ratioPadding = getVideoAspectRatioPadding($aspectRatio);

            return '
                <div class="widget-video" 
                        style="text-align:' . h($align) . ';
                        padding:' . h($padding) . 'px;
                        margin:' . h($margin) . 'px;
                        ' . h($customCss) . ' ">
                    <div style="
                        position:relative;
                        width:100%;
                        max-width:100%;
                        padding-top:' . h($ratioPadding) . ';
                        overflow:hidden;
                        border-radius:8px; ">
                        <iframe
                            src="https://www.youtube.com/embed/' . h($videoId) . '"
                            title="YouTube video player"
                            style="
                                position:absolute;
                                top:0;
                                left:0;
                                width:100%;
                                height:100%;
                                border:0;
                                ' . h($videoCss) . '
                            "
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            ';
        case 'divider':
            $size = $props['size'] ?? 48;
            $color = $props['color'] ?? 'var(--color-primary)';
            $name = $props['name'] ?? 'home';
            $padding = isset($props['padding']) ? ((int)$props['padding'] . 'px') : '0px';
            $margin = isset($props['margin']) ? ((int)$props['margin'] . 'px') : '0px';
            $height = isset($props['height']) ? ((int)$props['height'] . 'px') : '1px';
            $lineaCss = $props['lineaCss'] ?? '';
            $iconaCss = $props['iconaCss'] ?? '';

            return '<div class="widget-divider" 
                        style="display:flex;
                            align-items: center;
                            justify-content:center;
                            align-items:' . h($align) . ';
                            padding:' . h($padding) . ';
                            margin:' . h($margin) . ';">
                        <div style="flex: 1; height:'. h($height).'; 
                                background:'. h($color).';
                                ' . h($lineaCss) . '">
                        </div>
                        <div>
                            <span class="material-symbols-outlined" 
                                style="font-size:' . h($size) . 'px!important;
                                color:' . h($color) . ';
                                padding:' . h($padding) . ';
                                margin:' . h($margin) . ';
                                ' . h($iconaCss) . '">
                                ' . h($name) . '
                            </span>
                        </div>
                        <div style="flex: 1; 
                                height:'. h($height).'; 
                                background:'. h($color).';
                                ' . h($lineaCss) . '">
                        </div>
            </div>';
        
        
        default:
            return '<div class="widget-unknown">Widget non supportato: ' . h($type) . '</div>';
    }
}

//***********************************
// Render colonna in HTML pubblico
//***********************************     
function renderColumnHTML(array $column): string
{
    $width     = $column['width'] ?? 100;
    $padding   = isset($column['padding']) ? ((int)$column['padding'] . 'px') : '0px';
    $margin    = isset($column['margin']) ? ((int)$column['margin'] . 'px') : '0px';
    $background = $column['background'] ?? ($column['sfondoColor'] ?? 'transparent');
    $customCss = $column['customCss'] ?? '';

    $widgets = $column['widgets'] ?? [];

    $html = '<div class="page-column" style="
        flex-basis:' . h($width) . '%;
        padding:' . h($padding) . ';
        margin:' . h($margin) . ';
        background:' . h($background) . ';
        ' . h($customCss) . '
    ">';

    foreach ($widgets as $widget) { 
        $html .= renderWidgetHTML($widget);
    }

    $html .= '</div>';

    return $html;
}

/************************************
 * Render sezione in HTML pubblico
 ************************************/
function renderSectionHTML(array $section): string
{
    $background = $section['background'] ?? 'transparent';
    $padding    = isset($section['padding']) ? ((int)$section['padding'] . 'px') : '20px';
    $margin     = isset($section['margin']) ? ((int)$section['margin'] . 'px') : '0px';
    $customCss  = $section['customCss'] ?? '';

    $columns    = $section['columns'] ?? [];

    $html = '<section class="page-section" style="
        background:' . h($background) . ';
        padding:' . h($padding) . ';
        margin:' . h($margin) . ';
        ' . h($customCss) . '
    ">';

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

    return $body;
}//============================ 
// Risolve URL asset (immagini) per HTML pubblico
//============================
function resolveAssetUrl(string $path): string
{
    $baseUrl = '/FB-JSON';

    if ($path === '') return '';
    if (preg_match('#^https?://#', $path)) return $path;
    if (strpos($path, '/') === 0) return $path;

    return $baseUrl . '/' . ltrim($path, '/');
}