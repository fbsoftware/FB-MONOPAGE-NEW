<?php
/** Configurazione del sito */
?>
<h1>Configurazione</h1>

<div id="configuration-content" class="configuration-form">
<div class="config-section-title">
    Colori globali
</div>
    <label for="config-color-primary">Colore primario:</label>
    <input id="config-color-primary" type="color">

    <label for="config-color-secondary">Colore secondario:</label>
    <input id="config-color-secondary" type="color">

    <label for="config-color-accent">Colore accento:</label>
    <input id="config-color-accent" type="color">

    <label for="config-color-text">Colore testo:</label>
    <input id="config-color-text" type="color">

    <label for="config-color-bg">Colore sfondo:</label>
    <input id="config-color-bg" type="color">

    <div class="config-section-title">
    Tipografia
</div>
    <label for="config-heading-family">Font heading:</label>
    <select id="config-heading-family">
        <option value="Inter">Inter</option>
        <option value="Montserrat">Montserrat</option>
        <option value="Poppins">Poppins</option>
        <option value="Roboto">Roboto</option>
    </select>

    <label for="config-heading-weight">Peso font heading:</label>
    <select id="config-heading-weight">
        <option value="400">Regular</option>
        <option value="600">Semi-bold</option>
        <option value="700">Bold</option>
    </select>


    <label for="config-body-family">Font body:</label>
    <select id="config-body-family">
        <option value="Inter">Inter</option>
        <option value="Montserrat">Montserrat</option>
        <option value="Poppins">Poppins</option>
        <option value="Roboto">Roboto</option>
    </select>

    <label for="config-body-weight">Peso font body:</label>
    <select id="config-body-weight">
        <option value="400">Regular</option>
        <option value="500">Medium</option>
        <option value="600">Semi-bold</option>
        <option value="700">Bold</option>
    </select>


    <label for="config-font-h1">Dimensione H1:</label>
    <input id="config-font-h1" type="number">

    <label for="config-font-h2">Dimensione H2:</label>
    <input id="config-font-h2" type="number">

    <label for="config-font-h3">Dimensione H3:</label>
    <input id="config-font-h3" type="number">

    <label for="config-font-body">Dimensione testo:</label>
    <input id="config-font-body" type="number">

    <label for="config-font-small">Testo piccolo:</label>
    <input id="config-font-small" type="number">

    <br><br>
<button id="saveSiteConfig">Salva config</button>

<div id="siteConfigStatus"></div>

</div>