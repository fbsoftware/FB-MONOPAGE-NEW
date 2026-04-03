<?php
// $widget disponibile

$type = $widget['type'] ?? null;

if (!$type) {
    return;
}

$widgetFile = __DIR__ . '/../widgets/' . $type . '.php';

if (file_exists($widgetFile)) {
    include $widgetFile;
} else {
    echo "<!-- widget '$type' non trovato -->";
}
