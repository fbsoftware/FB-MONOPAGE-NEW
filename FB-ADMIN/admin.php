<?php

require_once dirname(__DIR__) . '/FB-EDITOR/config/app.php';

?>
<!DOCTYPE html>
<html lang="it">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>FB Admin</title>

    <link rel="stylesheet"
          href="<?= APP_URL ?>/FB-ADMIN/assets/css/admin.css">

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined"
          rel="stylesheet">

</head>

<body>

<div class="admin-layout">

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">

        <div class="admin-logo">
            FB-ADMIN
        </div>

        <nav class="admin-menu">

<!-- dashboard -------------------------------------------------->
            <a href="#" class="admin-menu-item active">

                <span class="material-symbols-outlined">
                    dashboard
                </span>

                Bacheca

            </a>

<!-- media -------------------------------------------------->
            <a href="#" class="admin-menu-item">

                <span class="material-symbols-outlined">
                    perm_media
                </span>

                Media

            </a>

<!-- pages -------------------------------------------------->
<a href="#" class="admin-menu-item" data-view="pages">

    <span class="material-symbols-outlined">
        description
    </span>

    Pagine

</a>

<!-- templates -------------------------------------------------->
<a href="#" class="admin-menu-item" data-view="templates">
                <span class="material-symbols-outlined">
                    dashboard_customize
                </span>

                Templates

            </a>

<!--   appearance -------------------------------------------------->
            <div class="admin-menu-group">

                <div class="admin-menu-item">

                    <span class="material-symbols-outlined">
                        palette
                    </span>

                    Aspetto

                </div>

            <a href="#" class="admin-submenu-item" data-view="menu">
                Menu
            </a>

            </div>

<!-- settings -------------------------------------------------- -->
            <a href="#" class="admin-menu-item">

                <span class="material-symbols-outlined">
                    settings
                </span>

                Configurazione

            </a>

        </nav>

    </aside>


    <!-- CONTENUTO -->
    <main class="admin-content" id="admin-content">

        <h1>Bacheca</h1>

        <p>
            Benvenuto in FB-ADMIN.
        </p>
            <img src="<?= APP_URL ?>/FB-ADMIN/assets/images/logo.png" alt="logo" class="admin-logo-image">

    </main>

</div>
<script> window.APP_URL = <?= json_encode(APP_URL) ?>;</script>
<script src="<?= APP_URL ?>/FB-ADMIN/assets/js/admin.js"></script>
<script src="<?= APP_URL ?>/FB-ADMIN/assets/js/pages.js"></script>
<script src="<?= APP_URL ?>/FB-ADMIN/assets/js/templates.js"></script>
<script src="<?= APP_URL ?>/FB-ADMIN/assets/js/menu.js"></script>
</body>
</html>