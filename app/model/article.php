<?php

/*récupération des articles par nom de catégorie depuis la base de données
 * @param string $category_name Le nom de la catégorie ("On n'est pas des pigeons")
 * @param int $limit Nombre maximum d'articles à récupérer (10 specs)
 * @return array Tableau contenant les articles trouvés
 */

function get_articles_by_category_name($category_name, $limit = 10) {
    try {
        // connexion à la base de données MySQL
        $dsn = "mysql:host=localhost;dbname=press_2024_v03;port=3307;charset=utf8mb4";
        $pdo = new PDO($dsn, "root", "");

        // préparation de la requête SQL pour sélectionner les articles
        // cette requête récupère les articles liés à une catégorie spécifique
        $query = "SELECT t_article.id_art, t_article.image_art, t_article.title_art, 
                        t_article.hook_art, t_article.content_art, t_article.date_art
                 FROM t_article 
                 INNER JOIN t_category ON t_category.id_cat = t_article.fk_category_art 
                 WHERE t_category.name_cat = :category_name 
                 LIMIT :limit";

        // exécution de la requête avec les paramètres
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':category_name', $category_name, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        // récupération des résultats sous forme de tableau associatif
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // en cas d'erreur, l'enregistrer dans les logs et retourner un tableau vide
        error_log("Erreur lors de la récupération des articles : " . $e->getMessage());
        return [];
    }
}

/**
 * récupère les données d'un article à partir de son ID
 * @param int $art_id ID de l'article à récupérer
 * @return array Données de l'article
 */
function get_article_a($art_id = 1)
{
    try {
        // connexion à la base de données
        $dsn = "mysql:host=localhost;dbname=press_2024_v03;port=3307;charset=utf8mb4";
        $pdo = new PDO($dsn, "root", "");

        // requête SQL pour récupérer l'article
        $query = "SELECT t_article.*, t_category.name_cat as category_name
                 FROM t_article 
                 LEFT JOIN t_category ON t_category.id_cat = t_article.fk_category_art
                 WHERE t_article.id_art = :art_id";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':art_id', $art_id, PDO::PARAM_INT);
        $stmt->execute();

        // récupère les résultats
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        // si l'article n'est pas trouvé, tenter de récupérer le premier article
        if (!$article) {
            $query = "SELECT t_article.*, t_category.name_cat as category_name
                     FROM t_article 
                     LEFT JOIN t_category ON t_category.id_cat = t_article.fk_category_art
                     ORDER BY t_article.id_art ASC
                     LIMIT 1";

            $stmt = $pdo->query($query);
            $article = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return $article ? $article : [];

    } catch (PDOException $e) {
        // en cas d'erreur, enregistrer dans les logs et retourner un tableau vide
        error_log("Erreur lors de la récupération de l'article: " . $e->getMessage());
        return [];
    }
}

function get_articles_by_category($category_id) {
    $dsn = "mysql:host=localhost;dbname=press_2024_v03;port=3307;charset=utf8mb4";
    $pdo = new PDO($dsn, "root", "");
    $q = "SELECT id_art, image_art, title_art, content_art, date_art 
          FROM t_article 
          WHERE fk_category_art = :category_id";
    $stmt = $pdo->prepare($q);
    $stmt->execute([':category_id' => $category_id]);
    return $stmt->fetchAll();
}

/**
 * retourne tous les articles de la db
 * @return mixed
 */

function get_all_article_a()
{
    switch (DATABASE_TYPE) {
        case  "SQL":
            return get_all_article_a_sql();
        case  "JSON":
            return get_all_article_a_json();
    }
}

function get_all_article_a_json()
{
    $fn = DATABASE_NAME;
    $art_db_s = file_get_contents($fn);
    $art_db_a = json_decode($art_db_s, true);
    return $art_db_a;
}


function get_all_article_a_sql():array
{
    $q = <<< SQL
        SELECT 
            ident_art AS id,
            title_art AS title,
            hook_art AS hook,
            content_art AS content,
            fk_category_art AS category
        FROM t_article 
        ORDER by ident_art DESC 
        LIMIT 5
SQL;
    $pdo = get_pdo();
    $stmt = $pdo->query($q);

    $result = $stmt->fetchAll();
    return $result;
}