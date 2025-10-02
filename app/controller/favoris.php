<?php
/**
 * Contrôleur principal pour la gestion des favoris
 * @return string HTML généré
 */
function main_favorite()
{
    // vérifie si l'utilisateur est connecté
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        // Si non connecté, rediriger vers la page d'accueil
        header('Location: ?page=home');
        exit;
    }
    // gère les actions de favori
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        $article_id = isset($_GET['article_id']) ? intval($_GET['article_id']) : 0;

        switch ($action) {
            case 'add':
                add_to_favorites($article_id);
                break;

            case 'remove':
                remove_from_favorites($article_id);
                break;

            case 'clear':
                clear_favorites();
                break;
        }

        // redirection vers la page précédente en préservant les paramètres
        if (isset($_GET['return'])) {
            $return_page = $_GET['return'];

            // si on retourne à la page d'article, s'assurer de transmettre l'ID de l'article
            if ($return_page === 'article' && isset($_GET['art_id'])) {
                $art_id = intval($_GET['art_id']);
                header("Location: ?page=article&art_id=$art_id");
                exit;
            }

            // préserver les paramètres de recherche si nécessaire
            if ($return_page === 'search' && isset($_GET['date_min']) && isset($_GET['date_max'])) {
                $date_min = urlencode($_GET['date_min']);
                $date_max = urlencode($_GET['date_max']);
                header("Location: ?page=search&date_min=$date_min&date_max=$date_max");
                exit;
            }

            // redirection standard
            header("Location: ?page=$return_page");
            exit;
        }

        // par défaut, rediriger vers la page des favoris
        header("Location: ?page=favorite");
        exit;
    }

    // récupérer tous les articles favoris
    $favorite_articles = get_favorite_articles();

    // affiche la page des favoris
    return join("\n", [
        ctrl_head(),
        html_favorites($favorite_articles),
        html_foot()
    ]);
}