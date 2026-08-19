<?php
header('Content-Type: application/json');

$path = $_SERVER['DOCUMENT_ROOT'] . "/FB-JSON/data/";

$pages = [];

foreach (glob($path . "*.json") as $gx) {

    $file = basename($gx);

    if ($file === "site-menu.json") {
        continue;
    }

    $slug = basename($gx, ".json");

    $pages[] = [
        "title" => ucwords(str_replace(["-", "_"], " ", $slug)),
        "page"  => $slug,
        "file"  => $file
    ];
}

echo json_encode([
    "success" => true,
    "pages" => $pages
]);
exit;