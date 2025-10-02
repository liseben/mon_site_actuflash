<?php

function main_login()
{
    $action = @$_GET['action'] ?? "";
    $msg = '';

    // si c'est une déconnexion
    if( $action == 'logout' )
    {
        // supprime l'utilisateur du fichier des connectés
        if (isset($_SESSION['id'])) {
            remove_login_from_file($_SESSION['id']);
        }

        // l'utilisateur est en train de se délogguer
        session_unset();

        // redirection vers la page d'accueil après déconnexion
        header('Location: ?page=login');
        exit;
    }

    // si c'est une tentative de connexion
    if( ! empty($_POST['identifier']))
    {
        // l'utilisateur est en train de s'identifier
        list( $valide, $_SESSION['id'], $_SESSION['role'] ) = login_validate($_POST['identifier'], $_POST['mdp']);

        // si identification réussie
        if( $valide )
        {
            // sauvegarde de la connexion dans un fichier(consigne groupe R)
            save_login_to_file($_SESSION['id'], $_SESSION['role']);

            // $msg = "Vous etes connecté."; ici le message ne sert à rien puis que je fais une redirection en bas
            // redirection vers la page d'accueil après connexion réussie
            header('Location: ?page=home');
            exit;
        }
        else
        {
            // identification ratée
            session_unset();
            $msg = "Identifiant ou mot de passe incorrect.";
        }
    }

    // si on est ici, c'est qu'on affiche le formulaire de connexion
    return join( "\n", [
        ctrl_head(),
        html_open_form(),
        ($msg ? "<div class='alert alert-info'>{$msg}</div>" : ""),
        html_unidentified_user(),
        html_link_home(),
        html_close_form(),
        html_foot()
    ]);
}