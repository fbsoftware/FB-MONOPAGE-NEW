<?php
// Salva il file nella root del sito
require_once __DIR__ . '/FB-EDITOR/config/app.php';
require_once __DIR__ . '/FB-EDITOR/lib/site-renderer.php';
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

$rootDir  = __DIR__;             // root del sito FB-MONOPAGE
$dataDir  = $rootDir . '/data';  // qui vanno i JSON delle pagine del sito
$pagesDir = $rootDir . '/pages'; // qui vanno gli HTML generati

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
    $url = trim($url);

    if ($url === '') {
        return '';
    }

    // Accetta direttamente il solo ID YouTube
    if (preg_match('/^[A-Za-z0-9_-]{11}$/', $url)) {
        return $url;
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
    $baseUrl = APP_URL; // path pubblico del sito

    if ($path === '') return '';
    if (preg_match('#^https?://#', $path)) return $path;
    if (strpos($path, '/') === 0) return $path;

    return $baseUrl . '/' . ltrim($path, '/');
}

//=================================
// Render widget Hero Slide nel sito
//=================================
function renderHeroSlideWidgetHTML($widget)
{
    $p = $widget['props'] ?? [];

    $slides = $p['slides'] ?? [];

    if (!is_array($slides) || empty($slides)) {
        return '<div class="widget-hero-slide-empty">Nessuna slide disponibile</div>';
    }

    $height = intval($p['height'] ?? 500);
    $interval = intval($p['interval'] ?? 5000);

    $showArrows = filter_var(
        $p['showArrows'] ?? true,
        FILTER_VALIDATE_BOOLEAN,
        FILTER_NULL_ON_FAILURE
    );

    $showDots = filter_var(
        $p['showDots'] ?? true,
        FILTER_VALIDATE_BOOLEAN,
        FILTER_NULL_ON_FAILURE
    );

    if ($showArrows === null) $showArrows = true;
    if ($showDots === null) $showDots = true;

    $customClass = trim($p['customClass'] ?? '');
    $customCss = trim($p['customCss'] ?? '');

    $slidesHtml = '';

    foreach ($slides as $index => $slide) {

        $image = $slide['image'] ?? '';
        $title = $slide['title'] ?? '';
        $text = $slide['text'] ?? '';

        $activeClass = $index === 0 ? ' active' : '';

        $slidesHtml .= '
            <div class="slide hero-slide' . $activeClass . '">

                <img
                    src="' . h($image) . '"
                    alt=""
                    style="
                        width:100%;
                        height:' . $height . 'px;
                        object-fit:cover;
                        display:block;
                    "
                >

                <div class="hero-slide-overlay">
                    <div class="hero-slide-content">

                        ' . (
                            $title !== ''
                            ? '<h2 class="hero-slide-title">' . h($title) . '</h2>'
                            : ''
                        ) . '

                        ' . (
                            $text !== ''
                            ? '<div class="hero-slide-text">' . h($text) . '</div>'
                            : ''
                        ) . '

                    </div>
                </div>

            </div>
        ';
    }

    $arrowsHtml = '';

    if ($showArrows) {
        $arrowsHtml = '
            <button type="button" class="prev">&#10094;</button>
            <button type="button" class="next">&#10095;</button>
        ';
    }

    $dotsHtml = '';

    if ($showDots) {

        $dotsHtml = '<div class="slider-dots">';

        foreach ($slides as $index => $_slide) {

            $activeClass = $index === 0 ? ' active' : '';

            $dotsHtml .= '
                <button
                    type="button"
                    class="dot' . $activeClass . '"
                    data-slide="' . $index . '">
                </button>
            ';
        }

        $dotsHtml .= '</div>';
    }

    return '
        <div
            class="widget-slider widget-hero-slider ' . h($customClass) . '"
            data-interval="' . $interval . '"
            data-widget-id="' . h($widget['id'] ?? '') . '"
            style="' . h($customCss) . '"
        >
            <div class="slider-slides">
                ' . $slidesHtml . '
            </div>

            ' . $arrowsHtml . '
            ' . $dotsHtml . '
        </div>
    ';
}