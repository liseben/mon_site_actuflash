<?php

/**
 * fonction principale pour la page d'accueil
 * @return string Le code HTML complet de la page
 */
function main_home():string
{
    // vérifie si l'utilisateur est connecté
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        // si non connecté, affiche un message demandant de se connecter
        return join("\n", [
            ctrl_head(),
            html_login_required(),
            html_foot(),
        ]);
    }

    // si connecté, récupération les 10 premiers articles de la catégorie "On n'est pas des pigeons"
    $pigeons_articles = get_articles_by_category_name("On n'est pas des pigeons", 10);

    return join("\n", [
        ctrl_head(),
        html_home_main($pigeons_articles),
        html_foot(),
    ]);
}

