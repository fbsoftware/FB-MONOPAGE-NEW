<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
require_once dirname(__DIR__) . '/config/app.php';
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
//
//$html = renderPageHTML($data);
try {
    $html = renderPageHTML($data);
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Errore render HTML: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
    exit;
}
//--------------------------------
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

set_error_handler(function($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

//=================================
// Escape HTML 
//=================================
function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
//=================================
// Funzioni di rendering HTML video
//=================================
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

//=================================
// Calcola padding per aspect ratio video
//=================================
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
     switch ($type) {
        case 'text':
            $text  = $props['text'] ?? '';
            $align = $props['align'] ?? 'left';
            $color = $props['color'] ?? 'inherit';
            $padding = isset($props['padding']) ? ((int)$props['padding'] . 'px') : '0px';
            $margin = isset($props['margin']) ? ((int)$props['margin'] . 'px') : '0px';
            $customCss = $props['customCss'] ?? '';
            $customClass = $props['customClass'] ?? '';

            return '<div class="widget-text ' . h($customClass) . '" 
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
            $customClass = $props['customClass'] ?? '';

            return '<div class="widget-text ' . h($customClass) . '" 
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
            $customClass = $props['customClass'] ?? '';

            $allowed = ['h1','h2','h3','h4','h5','h6'];
            if (!in_array($level, $allowed, true)) {
                $level = 'h2';
            }

            return '<div class="widget-header ' . h($customClass) . '" style="text-align:' . h($align) . ';
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
            $customClass = $props['customClass'] ?? '';

            return '<div class="widget-button ' . h($customClass) . '" 
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
            $customClass = $props['customClass'] ?? '';

            return '<div class="widget-image ' . h($customClass) . '" 
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
            $customClass = $props['customClass'] ?? '';

            return '<div class="widget-spacer ' . h($customClass) . '" 
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
            $customClass = $props['customClass'] ?? '';

             return '<div class="widget-icon ' . h($props['customClass'] ?? '') . '" 
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
            $customClass = $props['customClass'] ?? '';

            $videoId = getYoutubeVideoId($url);

            if ($videoId === '') {
                return '';
            }

            $ratioPadding = getVideoAspectRatioPadding($aspectRatio);

            return '
                <div class="widget-video ' . h($customClass) . '" 
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
            $customClass = $props['customClass'] ?? '';

            return '<div class="widget-divider ' . h($customClass) . '" 
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
        case 'richtext':
            $html = $props['html'] ?? '';
            $padding = isset($props['padding']) ? ((int)$props['padding'] . 'px') : '0px';
            $margin = isset($props['margin']) ? ((int)$props['margin'] . 'px') : '0px';
            $customCss = $props['customCss'] ?? '';

            return '<div class="widget-richtext" style="
                padding:' . h($padding) . ';
                margin:' . h($margin) . ';
                ' . h($customCss) . '
            ">' . $html . '</div>';        
        case 'navbar':
            return renderNavbarWidgetHTML($widget);   
        case 'gallery':
            return renderGalleryWidgetHTML($widget);                 
        default:
                return '<div class="widget-unknown">Widget non supportato: ' . h($type) . '</div>';
        }

}

//------------------------------------------
// Render widget navbar in HTML pubblico
//------------------------------------------
function renderNavbarWidgetHTML($widget)
{
    $p = $widget['props'] ?? [];

    $align = $p['align'] ?? 'right';
    $gap = intval($p['gap'] ?? 20);
    $color = $p['color'] ?? '#000000';
    $fontSize = intval($p['fontSize'] ?? 16);
    $padding = intval($p['padding'] ?? 10);
    $customCss = $p['customCss'] ?? '';

    $justify = 'flex-end';

    if ($align === 'left') {
        $justify = 'flex-start';
    }

    if ($align === 'center') {
        $justify = 'center';
    }

    $items = $p['items'] ?? [];
    $links = '';

    foreach ($items as $item) {

        $label = $item['label'] ?? 'Voce';
        $type = $item['type'] ?? 'anchor';
        $target = $item['target'] ?? '';

        $href = '#';

        if ($type === 'anchor') {
            $href = '#' . ltrim($target, '#');
        }

        if ($type === 'page') {
            $href = preg_replace('/\.html$/', '', $target) . '.html';
        }

        if ($type === 'url') {
            $href = $target;
        }

        $links .= '
            <a href="' . h($href) . '" style="color:' . h($color) . ';">
                ' . h($label) . '
            </a>
        ';
    }

    return '
        <nav class="widget-navbar" style="
            display:flex;
            justify-content:' . h($justify) . ';
            align-items:center;
            gap:' . $gap . 'px;
            padding:' . $padding . 'px;
            font-size:' . $fontSize . 'px;
            ' . $customCss . '
        ">
            ' . $links . '
        </nav>
    ';
}

//------------------------------------------
// Render widget gallery in HTML pubblico
//------------------------------------------
function renderGalleryWidgetHTML($widget)
{ 
    $p = $widget['props'] ?? [];
    $folder = trim($p['folder'] ?? 'portfolio', '/');
     $columns = intval($p['columns'] ?? 3);
     $gap = intval($p['gap'] ?? 16);
     $radius = intval($p['radius'] ?? 8);
     $height = isset($p['height']) ? ((int)$p['height'] . 'px') : 'auto';

    $galleryDir = APP_ROOT . '/assets/galleries/' . $folder;

    $images = [];

    if (is_dir($galleryDir)) {

        $files = scandir($galleryDir);
        sort($files);

        foreach ($files as $file) {

            if ($file === '.' || $file === '..') {
                continue;
            }

            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                $images[] = ASSETS_URL . '/galleries/' . $folder . '/' . $file;
            }
        }
    }

    $html = '';

    foreach ($images as $src) {
        $html .= '
            <div class="widget-gallery-item">
                <img src="' . h($src) . '" style="
                    border-radius:' . $radius . 'px;
                    width:100%;
                    height:' . h($height) . '; " >
            </div>
        ';
    }

    return '
        <div class="widget-gallery" style="
            display:grid;
            grid-template-columns:repeat(' . $columns . ', 1fr);
            gap:' . $gap . 'px;
        ">
            ' . $html . '
        </div>
    ';
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
    $backgroundImage = $section['backgroundImage'] ?? '';
    $overlayColor    = $section['overlayColor'] ?? '#000000';
    $overlayOpacity  = $section['overlayOpacity'] ?? 0.4;
    $columns    = $section['columns'] ?? [];
    $height     = isset($section['height']) ? ((int)$section['height'] . 'px') : 'auto';
    $backgroundPositionY = isset($section['backgroundPositionY']) ? ((int)$section['backgroundPositionY'] . '%') : '50%';
    $anchor = $section['anchor'] ?? '';
    $bgStyle = '';

    if($backgroundImage){
        $bgStyle .= "
            background-image:url('" . h($backgroundImage) . "');
            background-size:cover;
            background-position:center ' . h($backgroundPositionY) . ';
            position:relative;
            overflow:hidden;
        ";
    }

    $html = '<section class="page-section" id="' . h($anchor) . '" style="
    background:' . h($background) . ';
    padding:' . h($padding) . ';
    margin:' . h($margin) . ';
    min-height:' . h($height) . ';
    background-position:center ' . h($backgroundPositionY) . ';
    ' . $bgStyle . '
    ' . h($customCss) . '
">';

if($backgroundImage){

    $html .= '<div class="section-overlay" style="
        position:absolute;
        inset:0;
        background:' . h($overlayColor) . ';
        opacity:' . h($overlayOpacity) . ';
        pointer-events:none;
    "></div>';
}

    $html .= '<div class="page-columns" style="position:relative;z-index:2;">';
 
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
    $baseUrl = '/FB-MONOPAGE';

    if ($path === '') return '';
    if (preg_match('#^https?://#', $path)) return $path;
    if (strpos($path, '/') === 0) return $path;

    return $baseUrl . '/' . ltrim($path, '/');
}