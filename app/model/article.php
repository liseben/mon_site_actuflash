<?php

/**
Retourne l'article sur la homepage
 */
function get_article_homepage()
{
    $fn = '../asset/database/article.json';
    $art_db_s = file_get_contents($fn);
    $art_db_a = json_decode($art_db_s, true);
    foreach( $art_db_a as $art_a)
    {
        if( $art_a["id"] == 1 ) break;
    }
    // $art_a possède les données de l'article id 1
    return $art_a ;
}

function get_articles_by_category($category_id) {
    $dsn = "mysql:host=localhost;dbname=press_2024_v03;port=3307;charset=utf8mb4";
    $pdo = new PDO($dsn, "root", "");
    $q = "SELECT id_art, image_art, title_art, content_art, date_art 
          FROM t_article 
          WHERE fk_category_art = :category_id";
    $stmt = $pdo->prepare($q);
    $stmt->execute([':category_id' => $category_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}