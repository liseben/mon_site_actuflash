<?php
/**
 * Ajoute un article aux favoris
 * @param int $article_id ID de l'article à ajouter
 * @return bool Succès de l'opération
 */
function add_to_favorites($article_id)
{
    // initialise le tableau des favoris si nécessaire
    if (!isset($_SESSION['favorites'])) {
        $_SESSION['favorites'] = [];
    }

    // ajoute l'article aux favoris s'il n'y est pas déjà
    if (!in_array($article_id, $_SESSION['favorites'])) {
        $_SESSION['favorites'][] = $article_id;
        return true;
    }

    return false;
}

/**
 * Retire un article des favoris
 * @param int $article_id ID de l'article à retirer
 * @return bool Succès de l'opération
 */
function remove_from_favorites($article_id)
{
    // si le tableau des favoris n'existe pas, rien à faire
    if (!isset($_SESSION['favorites'])) {
        return false;
    }

    // recherche l'article dans les favoris
    $key = array_search($article_id, $_SESSION['favorites']);

    // si l'article est trouvé, le retirer
    if ($key !== false) {
        unset($_SESSION['favorites'][$key]);
        // réindexe le tableau
        $_SESSION['favorites'] = array_values($_SESSION['favorites']);
        return true;
    }

    return false;
}

/**
 * Vide complètement les favoris
 * @return bool Succès de l'opération
 */
function clear_favorites()
{
    $_SESSION['favorites'] = [];
    return true;
}

/**
 * Vérifie si un article est dans les favoris
 * @param int $article_id ID de l'article à vérifier
 * @return bool True si l'article est dans les favoris, false sinon
 */
function is_favorite($article_id)
{
    if (!isset($_SESSION['favorites'])) {
        return false;
    }

    return in_array($article_id, $_SESSION['favorites']);
}

/**
 * Récupère tous les articles favoris
 * @return array Tableau d'articles favoris
 */
function get_favorite_articles()
{
    // si aucun favori, retourner un tableau vide
    if (!isset($_SESSION['favorites']) || empty($_SESSION['favorites'])) {
        return [];
    }

    // récupère les IDs des articles favoris
    $favorite_ids = $_SESSION['favorites'];

    try {
        // connexion à la base de données
        $dsn = "mysql:host=localhost;dbname=press_2024_v03;port=3307;charset=utf8mb4";
        $pdo = new PDO($dsn, "root", "");

        // convertir le tableau d'IDs en chaîne pour la requête SQL
        $ids_str = implode(',', array_map('intval', $favorite_ids));

        // si la liste est vide, retourner un tableau vide
        if (empty($ids_str)) {
            return [];
        }

        // requête pour récupérer les articles favoris
        $query = "SELECT id_art, image_art, title_art, hook_art, content_art, date_art 
                  FROM t_article 
                  WHERE id_art IN ($ids_str)";

        $stmt = $pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des articles favoris : " . $e->getMessage());
        return [];
    }
}

