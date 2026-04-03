<?php

function renderPage(string $json)
{
    $layout = json_decode($json, true);

    if (!$layout || empty($layout['sections'])) {
        return '';
    }

    ob_start();

    foreach ($layout['sections'] as $section) {
        include __DIR__ . '/section.php';
    }

    return ob_get_clean();
}
