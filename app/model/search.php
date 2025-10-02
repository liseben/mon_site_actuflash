<?php
/**
 * Recherche des articles selon plusieurs critères (mot-clé, temps de lecture, catégorie, date)
 * @param string $keyword Mot-clé à rechercher dans le titre ou le contenu
 * @param int $readtime_min Temps de lecture minimum en minutes
 * @param int $readtime_max Temps de lecture maximum en minutes
 * @param int|null $category_id ID de la catégorie (facultatif)
 * @param string|null $date_min Date minimum (format Y-m-d)
 * @param string|null $date_max Date maximum (format Y-m-d)
 * @return array Tableau contenant les articles trouvés
 */
function search_articles_by_criteria($keyword = '', $readtime_min = 0, $readtime_max = 60, $category_id = null, $date_min = null, $date_max = null) {
    try {
        // connexion à la base de données MySQL
        $dsn = "mysql:host=localhost;dbname=press_2024_v03;port=3307;charset=utf8mb4";
        $pdo = new PDO($dsn, "root", "");

        // construction de la requête SQL avec les conditions de base
        $query = "SELECT t_article.id_art, t_article.image_art, t_article.title_art, 
                        t_article.hook_art, t_article.content_art, t_article.date_art, 
                        t_article.readtime_art, t_category.name_cat
                 FROM t_article 
                 LEFT JOIN t_category ON t_category.id_cat = t_article.fk_category_art
                 WHERE 1=1";

        $params = [];

        // ajoute la condition pour le mot-clé si fourni
        if (!empty($keyword)) {
            $query .= " AND (t_article.title_art LIKE :keyword OR t_article.content_art LIKE :keyword)";
            $params[':keyword'] = "%$keyword%";
        }

        // ajoute la condition pour le temps de lecture
        $query .= " AND t_article.readtime_art BETWEEN :readtime_min AND :readtime_max";
        $params[':readtime_min'] = $readtime_min;
        $params[':readtime_max'] = $readtime_max;

        // ajoute la condition pour la catégorie si fournie
        if (!empty($category_id)) {
            $query .= " AND t_article.fk_category_art = :category_id";
            $params[':category_id'] = $category_id;
        }

        // ajoute les conditions pour les dates si fournies
        if (!empty($date_min)) {
            $query .= " AND t_article.date_art >= :date_min";
            $params[':date_min'] = $date_min;
        }

        if (!empty($date_max)) {
            $query .= " AND t_article.date_art <= :date_max";
            $params[':date_max'] = $date_max;
        }

        // trie par date décroissante
        $query .= " ORDER BY t_article.date_art DESC";

        // exécution de la requête avec les paramètres
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        // récupération des résultats sous forme de tableau associatif
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // en cas d'erreur, l'enregistrer dans les logs et retourner un tableau vide
        error_log("Erreur lors de la recherche d'articles: " . $e->getMessage());
        return [];
    }
}

/**
 * Récupère toutes les catégories pour le menu déroulant
 * @return array Liste des catégories
 */
function get_categories_for_dropdown() {
    try {
        $dsn = "mysql:host=localhost;dbname=press_2024_v03;port=3307;charset=utf8mb4";
        $pdo = new PDO($dsn, "root", "");
        $query = "SELECT id_cat, name_cat FROM t_category ORDER BY name_cat";
        $stmt = $pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des catégories: " . $e->getMessage());
        return [];
    }
}