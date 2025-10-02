<?php
function main_categories():string
{
    // gère les actions de favoris
    if (isset($_GET['action'])) {
        $article_id = isset($_GET['article_id']) ? intval($_GET['article_id']) : 0;

        if ($_GET['action'] == 'add_favorite' && $article_id) {
            add_to_favorites($article_id);
        }
        else if ($_GET['action'] == 'remove_favorite' && $article_id) {
            remove_from_favorites($article_id);
        }

        // redirige vers la même page sans les paramètres d'action
        if (isset($_GET['category_id'])) {
            header("Location: ?page=categories&category_id=" . $_GET['category_id']);
            exit;
        } else {
            header("Location: ?page=categories");
            exit;
        }
    }

    // récupère toutes les catégories
    $categorie_a = get_categories();

    // variable d'initialisation
    $selected_category_id = null;
    $selected_category_name = '';
    $articles = [];

    // vérifie si une catégorie est sélectionnée
    if (isset($_GET['category_id'])) {
        $selected_category_id = $_GET['category_id'];

        // récupère le nom de la catégorie
        foreach ($categorie_a as $category) {
            if ($category['id_cat'] == $selected_category_id) {
                $selected_category_name = $category['name_cat'];
                break;
            }
        }

        // si la catégorie existe, récupérer ses articles
        if (!empty($selected_category_name)) {
            $articles = get_articles_by_category($selected_category_id);
        }
    }

    // si une catégorie spécifique est demandée, afficher ses articles
    if ($selected_category_id) {
        return join("\n", [
            ctrl_head(),
            html_category_articles($selected_category_name, $articles, $selected_category_id),
            html_foot(),
        ]);
    }
    // sinon, afficher la liste des catégories
    else {
        return join("\n", [
            ctrl_head(),
            html_categories($categorie_a),
            html_foot(),
        ]);
    }
}