<?php

function html_head($memu_a = [], $user_id ="" , $user_role ="" )
{
    $debug = false;
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
        <script src="./js/internal/favorite.js"></script>
        <script src="./js/internal/counter.js"></script>
	</head>
	<body>
    <header>
        <h1 style="font-family: 'Segoe UI', sans-serif; font-size: 2.5em; font-weight: bold; display: flex; justify-content: center; align-items: center; gap: 10px; color: #e63946; letter-spacing: 1px; text-align: center;">
            <span style="color: #e63946;">Actu</span><span style="color: #1d3557;">Flash</span>
            <img src="./media/actuflash.png" alt="Logo ActuFlash" style="height: 50px;">
        </h1>
        <?php
        foreach ($memu_a as $memu) {
            $text = $memu[0];
            $link = $memu[1];
            $option = isset($memu[2]) ? "&name={$memu[2]}" : "";// on regarde si le troisième paramètre existe
            echo <<<HTML
            <a href="?page=$link$option">$text</a> |
HTML;

        }
        ?>
        Welcome, <?=$user_id?> (<?=$user_role?>).
    </header>
    <main>
    <?php

	if($debug)
	{
        var_dump($_COOKIE);
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
        <hr />
        <div class="footer-container">
            <div class="footer-top">
                <div class="footer-column">
                    <h3>Actualités</h3>
                    <ul>
                        <li><a href="#">Internationale</a></li>
                        <li><a href="#">Nationale</a></li>
                        <li><a href="#">Politique</a></li>
                        <li><a href="#">Économie</a></li>
                        <li><a href="#">Société</a></li>
                        <li><a href="#">Faits divers</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3>Sections Spéciales</h3>
                    <ul>
                        <li><a href="#">Sports</a></li>
                        <li><a href="#">Arts</a></li>
                        <li><a href="#">Cinéma</a></li>
                        <li><a href="#">Affaires</a></li>
                        <li><a href="#">Automobile</a></li>
                        <li><a href="#">Voyage</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3>Services</h3>
                    <ul>
                        <li><a href="#">Archives</a></li>
                        <li><a href="#">Météo</a></li>
                        <li><a href="#">Nécrologie</a></li>
                        <li><a href="#">Petites annonces</a></li>
                        <li><a href="#">Carrières</a></li>
                        <li><a href="#">Nous joindre</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3>Abonnement</h3>
                    <p style="color: #bbb; margin-bottom: 15px;">Recevez nos actualités directement dans votre boîte de réception</p>
                    <form class="newsletter-form">
                        <input type="email" placeholder="Votre courriel">
                        <button type="submit">S'abonner</button>
                    </form>
                </div>
            </div>

            <div class="footer-middle">
                <a href="#" class="footer-logo">Actu Flash</a>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i>F</a>
                    <a href="#"><i class="fab fa-twitter"></i>T</a>
                    <a href="#"><i class="fab fa-instagram"></i>I</a>
                    <a href="#"><i class="fab fa-youtube"></i>Y</a>
                    <a href="#"><i class="fab fa-linkedin-in"></i>L</a>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© 2025 ActuFlash. Tous droits réservés.</p>
                <p>Un site indépendant d'information générale sur l'actualité au Québec et à l'international.</p>

                <div class="legal-links">
                    <a href="#">Conditions d'utilisation</a>
                    <a href="#">Politique de confidentialité</a>
                    <a href="#">Gestion des cookies</a>
                    <a href="#">Plan du site</a>
                </div>
            </div>
        </div>
    </footer>
	</body>
	</html>
	<?php
	return ob_get_clean();
}

