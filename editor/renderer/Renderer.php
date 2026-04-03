<?php

class Renderer {

  protected string $theme;

  public function __construct(string $theme) {
    $this->theme = $theme;
  }

  public function render(array $layout): string {

    ob_start();

    require "themes/{$this->theme}/theme.php";

    return ob_get_clean();
  }

  // ==========================
  // SEZIONI
  // ==========================
  public function renderSections(array $sections): void {
    foreach ($sections as $section) {
      echo '<section class="section">';
      $this->renderColumns($section['columns']);
      echo '</section>';
    }
  }

  // ==========================
  // COLONNE
  // ==========================
  protected function renderColumns(array $columns): void {
    echo '<div class="section-inner">';
    foreach ($columns as $column) {
      echo '<div class="column">';
      $this->renderWidgets($column['widgets']);
      echo '</div>';
    }
    echo '</div>';
  }

  // ==========================
  // WIDGET
  // ==========================
  protected function renderWidgets(array $widgets): void {
    foreach ($widgets as $widget) {
      $this->renderWidget($widget);
    }
  }

  protected function renderWidget(array $widget): void {

    $type = $widget['type'];

    $widgetFile = "widgets/{$type}.php";

    if (!file_exists($widgetFile)) {
      echo "<!-- widget {$type} non trovato -->";
      return;
    }

    // props disponibili nel widget
    $props = $widget;

    require $widgetFile;
  }
}
