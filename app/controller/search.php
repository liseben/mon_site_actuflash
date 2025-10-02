<?php
/**
 * Contrôleur principal pour la recherche avec plusieurs critères
 * @return string HTML généré
 */
function main_search() {
    // vérifie si l'utilisateur est connecté
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        // si non connecté, rediriger vers la page d'accueil
        header('Location: ?page=home');
        exit;
    }

    // Initialisation des variables
    $keyword = '';
    $readtime_min = 0;
    $readtime_max = 60;
    $category_id = null;
    $date_min = '';
    $date_max = '';
    $error = '';
    $articles = [];

    // Récupérer les catégories pour le menu déroulant
    $categories = get_categories_for_dropdown();

    // si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // récupère les valeurs saisies
        $keyword = $_POST['keyword'] ?? '';
        $readtime_min = isset($_POST['readtime_min']) ? intval($_POST['readtime_min']) : 0;
        $readtime_max = isset($_POST['readtime_max']) ? intval($_POST['readtime_max']) : 60;
        $category_id = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
        $date_min = $_POST['date_min'] ?? '';
        $date_max = $_POST['date_max'] ?? '';

        // valide les temps de lecture
        if ($readtime_min < 0) $readtime_min = 0;
        if ($readtime_max < $readtime_min) $readtime_max = $readtime_min;
        if ($readtime_max > 120) $readtime_max = 120;

        // valide les dates
        if (!empty($date_min) && !empty($date_max)) {
            // Vérifier que date_min <= date_max
            if (strtotime($date_min) > strtotime($date_max)) {
                // Inverser les dates si nécessaire
                $temp = $date_min;
                $date_min = $date_max;
                $date_max = $temp;
            }
        }

        // recherche les articles
        $articles = search_articles_by_criteria($keyword, $readtime_min, $readtime_max, $category_id, $date_min, $date_max);
    }

    // génère le formulaire de recherche
    $form = html_search_form($keyword, $readtime_min, $readtime_max, $category_id, $date_min, $date_max, $categories, $error);

    // génère les résultats
    $results = html_search_results($articles, $keyword, $readtime_min, $readtime_max, $category_id, $date_min, $date_max, $categories);

    // création du second menu latéral avec le bouton rechercher
    $search_button_menu = <<<HTML
<div class="search-left-sidebar2">
    <h3 class="sidebar-title">Lancer la recherche</h3>
    <div class="sidebar-content">
        <button type="submit" form="search-form" class="sidebar-button-large">Rechercher</button>
        <div class="search-info">
            <p>Utilisez les critères de gauche pour affiner votre recherche, puis cliquez sur "Rechercher".</p>
        </div>
    </div>
</div>
HTML;

    return join("\n", [
        ctrl_head(),
        '<form id="search-form" action="?page=search" method="post">',
        '<div class="search-layout-4col">',
        $form,
        $search_button_menu,
        $results,
        '</div>',
        '</form>',
        html_foot()
    ]);
}