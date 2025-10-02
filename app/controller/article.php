<?php
/**
 * génère la page avec un seul artcle complet
 * @return string html
 */
function main_article()
{
    // assure que l'ID est bien un entier
    $art_id = isset($_GET["art_id"]) ? intval($_GET["art_id"]) : 1;

    // déboguage ID reçu
    error_log("ID d'article demandé : " . $art_id);

    // récupère les données de cet article
    $article_a = get_article_a($art_id);

    // vérifie que l'article a été trouvé
    if (empty($article_a)) {
        error_log("Article non trouvé pour l'ID : " . $art_id);
        // redirige ou affiche un message d'erreur
        header("Location: ?page=home");
        exit;
    }

    // énére la page html
    return join("\n", [
        ctrl_head(),
        html_article_main($article_a),
        html_foot(),
    ]);
}