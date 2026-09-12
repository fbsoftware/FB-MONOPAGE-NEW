<?php
require_once 'FB-EDITOR/config/app.php';

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function loadSiteMenu(): array
{
    $menuPath = __DIR__ . '/data/site-menu.json';

    if (!file_exists($menuPath)) {
        return [];
    }

    $json = file_get_contents($menuPath);
    $menu = json_decode($json, true);

    return is_array($menu) ? $menu : [];
}

function renderSiteMenuHTML(array $items): string
{
    if (empty($items)) {
        return '';
    }

    $html = '<ul class="site-menu">';
    $prevLevel = 0;

    foreach ($items as $i => $item) {
        $title = $item['title'] ?? 'Pagina';
        $page  = $item['page'] ?? '#';
        $level = isset($item['level']) ? (int)$item['level'] : 0;

        if ($i > 0) {
            if ($level > $prevLevel) {
                $html .= '<ul>';
            } elseif ($level < $prevLevel) {
                for ($j = $prevLevel; $j > $level; $j--) {
                    $html .= '</li></ul>';
                }
                $html .= '</li>';
            } else {
                $html .= '</li>';
            }
        }

        $html .= '<li>';
        $html .= '<a href="index.php?page=' . h($page) . '">' . h($title) . '</a>';

        $prevLevel = $level;
    }

    for ($j = $prevLevel; $j >= 0; $j--) {
        $html .= '</li></ul>';
    }

    return $html;
}

$siteMenu = loadSiteMenu();
$page = $_GET['page'] ?? 'home';
$page = preg_replace('/[^a-zA-Z0-9\-_]/', '', $page);

$pagePath = __DIR__ . '/pages/' . $page . '.html';

$pageContent = file_exists($pagePath)
    ? file_get_contents($pagePath)
    : '<p>Pagina non trovata</p>';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Sito di FB</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/site.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
<script>
document.addEventListener('DOMContentLoaded', () => {
    if(!document.querySelector('.gallery-lightbox-overlay')){
        const overlay = document.createElement('div');
        overlay.className = 'gallery-lightbox-overlay';
        overlay.innerHTML = '<img src="" alt="Immagine gallery">';
        document.body.appendChild(overlay);

        overlay.addEventListener('click', () => overlay.classList.remove('visible'));
    }

    document.querySelectorAll('.gallery-lightbox-trigger').forEach(img => {
        img.addEventListener('click', e => {
            const src = e.currentTarget.dataset.src;
            const overlay = document.querySelector('.gallery-lightbox-overlay');
            overlay.querySelector('img').src = src;
            overlay.classList.add('visible');
        });
    });
});
</script>
</head>
<body>

<main>

<section id="page-content">
    <?php echo $pageContent; ?>
</section>

<!-- Torna su in alto -->
<a id="back-to-top" href="#">↑</a>
<script>
//=======================================
//  Back to top button
//=======================================   
window.addEventListener("load", function() {

    const btn = document.getElementById("back-to-top");

    window.addEventListener("scroll", function() {

        if (window.scrollY > 300) {
            btn.classList.add("visible");
        } else {
            btn.classList.remove("visible");
        }

    });

});
</script>
</main>
<script src="<?= ASSETS_URL ?>/js/site-slider.js"></script>
</body>
</html>