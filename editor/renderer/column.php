<?php
// $column disponibile

echo '<div class="page-column" data-id="' . $column['id'] . '">';

foreach ($column['widgets'] as $widget) {
    include __DIR__ . '/widget.php';
}

echo '</div>';
