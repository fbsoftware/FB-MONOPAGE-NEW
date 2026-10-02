 // Render singolo widget in HTML pubblico
*/****************************************** */
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
            $height = isset($props['height']) ? ((int)$props['height'] . 'px') : 'auto';
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
        case 'imageSlide':
            return renderImageSlideWidgetHTML($widget); 
        case 'heroSlide':
            return renderHeroSlideWidgetHTML($widget);    
        case 'hero':
           return renderHeroWidgetHTML($widget);
        default:
                return '<div class="widget-unknown">Widget non supportato: ' . h($type) . '</div>';
        }

}
//=================================
// Render widget Hero nel sito
//=================================
function renderHeroWidgetHTML($widget)
{
    $p = $widget['props'] ?? [];

    $image = $p['image'] ?? '';
    $title = $p['title'] ?? '';
    $text = $p['text'] ?? '';

    $height = intval($p['height'] ?? 500);

    $overlay = filter_var(
        $p['overlay'] ?? true,
        FILTER_VALIDATE_BOOLEAN,
        FILTER_NULL_ON_FAILURE
    );

    if ($overlay === null) {
        $overlay = true;
    }

    $customClass = trim($p['customClass'] ?? '');
    $customCss = trim($p['customCss'] ?? '');

    if ($image === '') {
        return '<div class="widget-hero-placeholder">
                    Nessuna immagine selezionata
                </div>';
    }

    $overlayHtml = '';

    if ($overlay) {

        $titleHtml = '';

        if ($title !== '') {
            $titleHtml = '
                <h1 class="hero-title">' .
                    h($title) .
                '</h1>';
        }

        $textHtml = '';

        if ($text !== '') {
            $textHtml = '
                <div class="hero-text">' .
                    h($text) .
                '</div>';
        }

        $overlayHtml = '
            <div class="hero-overlay">
                <div class="hero-content">
                    ' . $titleHtml . '
                    ' . $textHtml . '
                </div>
            </div>';
    }

    return '
        <div
            class="widget-hero ' . h($customClass) . '"
            data-widget-id="' . h($widget['id'] ?? '') . '"
            style="
                position:relative;
                height:' . $height . 'px;
                overflow:hidden;
                ' . h($customCss) . '
            "
        >

            <img
                src="' . h($image) . '"
                alt=""
                style="
                    width:100%;
                    height:100%;
                    object-fit:cover;
                    display:block;
                "
            >

            ' . $overlayHtml . '

        </div>
    ';
}
// ------------------------------------------
// Render widget navbar in HTML pubblico
// ------------------------------------------

function renderNavbarWidgetHTML($widget)
{
    $p = $widget['props'] ?? [];

    $align = $p['align'] ?? 'right';
    $gap = intval($p['gap'] ?? 20);
    $orientation = $p['orientation'] ?? 'horizontal';
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


    // =================================================
    // SORGENTE DEL MENU
    // =================================================

    $source = $p['source'] ?? 'manual';

    if ($source === 'site') {

        $menuPath = __DIR__ . '/data/site-menu.json';

        if (file_exists($menuPath)) {

            $json = file_get_contents($menuPath);
            $siteMenu = json_decode($json, true);

            $items = is_array($siteMenu)
                ? $siteMenu
                : [];

        } else {

            $items = [];
        }

    } else {

        $items = $p['items'] ?? [];
    }


    // =================================================
    // COSTRUISCE ALBERO DEL MENU
    // =================================================

    $buildMenuTree = function($items) {

        $root = [];
        $stack = [];

        foreach ($items as $item) {

            $level = max(
                0,
                intval($item['level'] ?? 0)
            );

            $node = $item;
            $node['children'] = [];

            if ($level === 0) {

                $root[] = $node;

                $index = count($root) - 1;

                $stack[0] = &$root[$index];

                $stack = array_slice($stack, 0, 1);

                unset($node);

                continue;
            }

            if (isset($stack[$level - 1])) {

                $stack[$level - 1]['children'][] = $node;

                $index = count(
                    $stack[$level - 1]['children']
                ) - 1;

                $stack[$level] =
                    &$stack[$level - 1]['children'][$index];

                $stack = array_slice(
                    $stack,
                    0,
                    $level + 1
                );

            } else {

                // Livello non valido:
                // riportiamo la voce alla radice

                $root[] = $node;

                $index = count($root) - 1;

                $stack[0] = &$root[$index];

                $stack = array_slice($stack, 0, 1);
            }

            unset($node);
        }

        return $root;
    };


    $menuTree = $buildMenuTree($items);


    // =================================================
    // RENDER RICORSIVO DEL MENU
    // =================================================

    $renderMenuTree = function($nodes) use (
        &$renderMenuTree,
        $color
    ) {

        $html = '';

        foreach ($nodes as $item) {

            $label = $item['label']
                ?? $item['title']
                ?? 'Voce';

            $type = $item['type'] ?? 'anchor';
            $target = $item['target'] ?? '';

            $href = '#';


            // -----------------------------------------
            // Anchor
            // -----------------------------------------

            if ($type === 'anchor') {

                $href = '#' . ltrim($target, '#');
            }


            // -----------------------------------------
            // Pagina
            // -----------------------------------------

if ($type === 'page') {

    $page = preg_replace(
        '/\.html$/',
        '',
        $target
    );

    $href =
        '/FB-MONOPAGE-NEW/index.php?page=' .
        $page;
}


            // -----------------------------------------
            // Link esterno
            // -----------------------------------------

            if (
                $type === 'external' ||
                $type === 'url'
            ) {

                $href = $target;
            }


            $html .= '
                <li class="navbar-item">

                    <a
                        href="' . h($href) . '"
                        style="color:' . h($color) . ';"
                    >
                        ' . h($label) . '
                    </a>
            ';


            // -----------------------------------------
            // Eventuali figli
            // -----------------------------------------

            if (
                !empty($item['children']) &&
                is_array($item['children'])
            ) {

                $html .= '
                    <ul class="navbar-submenu">
                        ' .
                        $renderMenuTree(
                            $item['children']
                        ) .
                    '
                    </ul>
                ';
            }


            $html .= '
                </li>
            ';
        }

        return $html;
    };


    // =================================================
    // HTML FINALE
    // =================================================

    $links = '
        <ul class="navbar-menu">
            ' .
            $renderMenuTree($menuTree) .
            '
        </ul>
    ';
    $orientationClass =
        $orientation === 'vertical'
            ? 'vertical'
            : 'horizontal';

    return '
        <nav class="widget-navbar ' . h($orientationClass) . '" style="
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
        <img 
            src="' . h($src) . '" 
            data-src="' . h($src) . '" 
            class="gallery-lightbox-trigger"
            style="
                width:100%;
                display:block;
                border-radius:' . $radius . 'px;
                height:' . $height . 'px;
                object-fit:cover;
            ">
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


//------------------------------------------
// Render widget Image Slide nel sito
//------------------------------------------
function renderImageSlideWidgetHTML($widget)
{
    $p = $widget['props'] ?? [];

    $folder = trim($p['folder'] ?? 'portfolio', '/');
    $height = intval($p['height'] ?? 400);
    $interval = intval($p['interval'] ?? 5000);

    /*
     * Compatibilità con booleani veri
     * e vecchi valori salvati come stringhe.
     */
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

    if ($showArrows === null) {
        $showArrows = true;
    }

    if ($showDots === null) {
        $showDots = true;
    }

    /*
     * Path filesystem:
     * usato da PHP per leggere la cartella.
     */
    $sliderDir = APP_ROOT . '/assets/galleries/' . $folder;

    /*
     * URL pubblici:
     * usati negli attributi src delle immagini.
     */
    $images = [];

    if (is_dir($sliderDir)) {

        $files = scandir($sliderDir);
        sort($files, SORT_NATURAL | SORT_FLAG_CASE);

        foreach ($files as $file) {

            if ($file === '.' || $file === '..') {
                continue;
            }

            $filePath = $sliderDir . '/' . $file;

            if (!is_file($filePath)) {
                continue;
            }

            $ext = strtolower(
                pathinfo($file, PATHINFO_EXTENSION)
            );

            if (
                in_array(
                    $ext,
                    ['jpg', 'jpeg', 'png', 'webp', 'gif'],
                    true
                )
            ) {
                $images[] =
                    ASSETS_URL .
                    '/galleries/' .
                    rawurlencode($folder) .
                    '/' .
                    rawurlencode($file);
            }
        }
    }

    if (!$images) {
        return '
            <div class="widget-slider-empty">
                Nessuna immagine nella cartella:
                ' . h($folder) . '
            </div>
        ';
    }

    $slidesHtml = '';

    foreach ($images as $index => $src) {

        $activeClass = $index === 0
            ? ' active'
            : '';

        $slidesHtml .= '
            <div class="slide' . $activeClass . '">
                <img
                    src="' . h($src) . '"
                    alt=""
                    style="
                        width:100%;
                        height:' . $height . 'px;
                        display:block;
                        object-fit:cover;
                    "
                >
            </div>
        ';
    }

    $arrowsHtml = '';

    if ($showArrows) {
        $arrowsHtml = '
            <button
                type="button"
                class="prev"
                aria-label="Immagine precedente">
                &#10094;
            </button>

            <button
                type="button"
                class="next"
                aria-label="Immagine successiva">
                &#10095;
            </button>
        ';
    }

    $dotsHtml = '';

    if ($showDots) {

        $dotsHtml = '
            <div class="slider-dots">
        ';

        foreach ($images as $index => $_src) {

            $activeClass = $index === 0
                ? ' active'
                : '';

            $dotsHtml .= '
                <button
                    type="button"
                    class="dot' . $activeClass . '"
                    data-slide="' . $index . '"
                    aria-label="Vai all’immagine ' . ($index + 1) . '">
                </button>
            ';
        }

        $dotsHtml .= '</div>';
    }

    return '
        <div
            class="widget-slider"
            data-widget-id="' . h($widget['id'] ?? '') . '"
            data-interval="' . $interval . '"
        >
            <div class="slider-slides">
                ' . $slidesHtml . '
            </div>

            ' . $arrowsHtml . '
            ' . $dotsHtml . '
        </div>
    ';
}
//***********************************
// Render colonna in HTML pubblico
//***********************************     
function renderColumnHTML(array $column): string
{
    $width     = $column['width'] ?? 100;
    $height     = $column['height'] ?? 100;
    $padding   = isset($column['padding']) ? ((int)$column['padding'] . 'px') : '0px';
    $margin    = isset($column['margin']) ? ((int)$column['margin'] . 'px') : '0px';
    $background = $column['background'] ?? ($column['sfondoColor'] ?? 'transparent');
    $customCss = $column['customCss'] ?? '';

    $widgets = $column['widgets'] ?? [];

    $html = '<div class="page-column" style="
        flex-basis:' . h($width) . '%;
        height:' . h($height) . 'px;
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
