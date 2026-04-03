<?php
// $section disponibile

echo '<section class="page-section" data-id="' . $section['id'] . '">';

foreach ($section['columns'] as $column) {
    include __DIR__ . '/column.php';
}

echo '</section>';
