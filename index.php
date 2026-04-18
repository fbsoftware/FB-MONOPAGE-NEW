<?php
$page = $_GET['page'] ?? 'home';

// sicurezza base
$page = preg_replace('/[^a-zA-Z0-9\-_]/', '', $page);

$file = __DIR__ . '/pages/' . $page . '.html';

if (!file_exists($file)) {
    $file = __DIR__ . '/pages/404.html';
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Sito di FB</title>
    <link rel="stylesheet" href="/FB-JSON/assets/css/site.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>
<body>

<main>
<?php include __DIR__ . '/includes/nav.php'; 
    if (file_exists($file)) {
        readfile($file);
    } else {
        echo "<h1>Pagina non trovata</h1>";
    }
    ?>
</main>

</body>
</html>