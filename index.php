<?php
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
    <link rel="stylesheet" href="/FB-MONOPAGE/assets/css/site.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>
<body>

<main>
<?php
require_once __DIR__ . '/config/app.php';
?>
<!-- Header e menu 
<div style="display: flex; 
            flex-direction: row; 
            align-items: center;
            width: 1240px; 
            background: #cacaca; 
            padding: 10px;  
            height: 140px;
            ">

    <header class="header" >
        <img src="assets/images/logo.png" alt="Logo di FB" class="logo" height="150px" 
                style="padding: 20px;"   >
    </header>

        <nav>
            <?php echo renderSiteMenuHTML(loadSiteMenu()); ?>
        </nav> 
    
</div>
-->
<section id="page-content">
    <?php echo $pageContent; ?>
</section>

<footer style="background: #cacaca; 
                padding: 10px; 
                text-align: center; 
                font-size: 14px;
                width: 1240px;
                ">
    &copy; 2026 FB. Tutti i diritti riservati.
</footer>   
</main>

</body>
</html>