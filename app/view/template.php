<?php

function html_head($memu_a = [], $user_id = "", $user_role = "")
{
    $debug = false;

    // obtenir la page actuelle pour maintenir le contexte
    $current_page = $_GET['page'] ?? 'home';
    $page_params = '';

    // préserve les autres paramètres d'URL, comme les paramètres de recherche
    foreach ($_GET as $key => $value) {
        if ($key !== 'page') {
            $page_params .= "&{$key}=" . urlencode($value);
        }
    }

    ob_start();
    ?>
    <html lang="fr">
    <head>
        <title>ActuFlash template MVC</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="./css/internal/main.css" /> <!-- lib interne / perso -->
        <script
                src="https://code.jquery.com/jquery-3.4.1.js"
                integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
                crossorigin="anonymous"></script>
        <script src="./js/quirks/QuirksMode.js"></script>
        <script src="./js/internal/articles.js"></script>
        <script src="./js/internal/detail.js"></script>
        <script src="./js/internal/theme.js"></script>
        <?php if (AJAX_ENABLED): ?>
            <script src="./js/internal/menu.js"></script>
        <?php endif; ?>
        <?php if (AJAX_ENABLED): ?>
            <script src="./js/internal/favorite.js"></script>
        <?php endif; ?>
    </head>
    <body class="yellow fontsize-medium"> <!-- Classe par défaut, JavaScript les mettra à jour -->
    <header class="full-width-header">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                    <img src="./media/actuflash.png" alt="Logo ActuFlash" style="height: 40px;">
                    <span><span style="color: #e63946;">Actu</span><span style="color: #1d3557;">Flash</span></span>
                </a>
                <button
                        class="navbar-toggler"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarContent"
                        aria-controls="navbarContent"
                        aria-expanded="false"
                        aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0" id="dynamic-menu">
                        <?php if (AJAX_ENABLED): ?>
                            <!-- Menu dynamique AJAX -->
                            <li class="nav-item">
                                <span class="nav-link">Chargement...</span>
                            </li>
                        <?php else: ?>
                            <!-- menu statique classique -->
                            <?php
                            foreach ($memu_a as $memu) {
                                $text = $memu[0];
                                $link = $memu[1];
                                $option = isset($memu[2]) ? "&name={$memu[2]}" : "";
                                echo <<<HTML
              <li class="nav-item">
                <a class="nav-link" href="?page=$link$option">$text</a>
              </li>
HTML;
                            }
                            ?>
                        <?php endif; ?>
                    </ul>

                    <!-- sélecteur de fond (utilisant JavaScript) -->
                    <div class="me-3">
                        <select id="background" class="form-select form-select-sm">
                            <option value="yellow">Jaune</option>
                            <option value="rose">Rose</option>
                            <option value="blue">Bleu</option>
                        </select>
                    </div>

                    <!-- sélecteur de taille de police (utilisant JavaScript) -->
                    <div class="me-3">
                        <select id="fontsize" class="form-select form-select-sm">
                            <option value="small">Petite</option>
                            <option value="medium">Moyenne</option>
                            <option value="large">Grande</option>
                        </select>
                    </div>

                    <!-- bouton de déconnexion -->
                    <?php if(isset($_SESSION['id'])) : ?>
                        <!-- si l'utilisateur est connecté, affichr le bouton de déconnexion -->
                        <a href="?page=login&action=logout" class="btn btn-danger btn-sm">Logout</a>
                    <?php else : ?>
                        <!-- si l'utilisateur n'est pas connecté, affiche le bouton de connexion -->
                        <a href="?page=login&action=login" class="btn btn-primary btn-sm">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>
    <main>
    <?php

    if($debug)
    {
        var_dump($_SESSION);
        var_dump($_GET);
        var_dump($_POST);
    }
    return ob_get_clean();
}

function html_foot()
{
    ob_start();
    ?>

    </main>
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-middle">
                <a href="#" class="footer-logo">Actu Flash</a>
            </div>

            <div class="footer-bottom">
                <p>© 2025 ActuFlash. Tous droits réservés.</p>
                <p>Un site indépendant d'information générale sur l'actualité en Belgique et à l'international.</p>
            </div>
        </div>
    </footer>

    </body>
    </html>
    <?php
    return ob_get_clean();
}