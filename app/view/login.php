<?php

/**
 * bouton logout à afficher
 */
function html_logout_button()
{
	ob_start();
	?>
    <a href="?page=login&action=logout">log out</a>
	<?php
	return ob_get_clean();
}

/**
 * bouton login à afficher
 */
function html_login_button($user="inconnu")
{
	ob_start();
	?>
    <a href="?page=login&action=login">log in</a>
    <?php
	return ob_get_clean();
}

/**
 * open form
 */
function html_open_form()
{
	ob_start();
	?>
    <form method="post">
	<?php
	return ob_get_clean();
}

/**
 * close form
 */
function html_close_form()
{
	ob_start();
	?>
    </form>
	<?php
	return ob_get_clean();
}

/**
 *
 */
function html_link_home()
{
    ob_start();
    ?>
    <p>
        <a href="."><h4>go to HOME</h4></a>
    </p>
    <?php
    return ob_get_clean();
}

function html_unidentified_user():string
    {
        return <<< HTML
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">Connexion</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="identifier" class="form-label">Identifiant:</label>
                                <input type="text" class="form-control" id="identifier" name="identifier" placeholder="identifiant" required>
                                <label for="mdp" class="form-label">Mot de passe:</label>
                                <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Mot de passe" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Se connecter</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    HTML;
    }


?>