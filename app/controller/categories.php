require_once '../model/categories.php';
require_once '../model/article.php';
<?php
function main_categories():string
{
    $categorie_a = get_categories();
    $selected_category = $_GET['category'] ?? null;
    var_dump($selected_category);
    $articles = [];

    if ($selected_category) {
        foreach ($categorie_a as $category) {
            if ($category['name_cat'] === $selected_category) {
                $articles = get_articles_by_category($category['id_cat']);
                var_dump($articles);
                break;
            }
        }
    }

    return join( "\n", [// permet la concaténation des chaines de caractère avec comme séparateur le saut à la ligne
        ctrl_head(),
        html_categories($categorie_a, $selected_category, $articles),
        html_foot(),
    ]);

}