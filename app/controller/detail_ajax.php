<?php
/**
 * Contrôleur AJAX pour récupérer les détails d'un article
 * @return string JSON des détails de l'article
 */
function main_detail_ajax()
{
    // vérifie si l'utilisateur est connecté
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        return json_encode(['error' => 'Non autorisé']);
    }

    // récupère le rôle de l'utilisateur
    $user_role = $_SESSION['role'] ?? '';

    // vérifie que l'ID de l'article est bien fourni
    if (!isset($_GET['art_id'])) {
        return json_encode(['error' => 'ID d\'article manquant']);
    }

    $article_id = $_GET['art_id'];

    try {
        // connexion à la base de données
        $dsn = "mysql:host=localhost;dbname=press_2024_v03;port=3307;charset=utf8mb4";
        $pdo = new PDO($dsn, "root", "");

        // requête SQL différente selon le rôle
        if ($user_role === 'administrateur' || $user_role === 'Super Administrateur') {
            // pour les admins, on récupère toutes les informations
            $query = "SELECT a.*, c.name_cat as category_name
                     FROM t_article a
                     LEFT JOIN t_category c ON c.id_cat = a.fk_category_art
                     WHERE a.id_art = :art_id";
        } else {
            // pour les utilisateurs normaux, on récupère seulement les infos de base
            $query = "SELECT a.id_art, a.title_art, a.hook_art, a.content_art, a.date_art, a.image_art
                     FROM t_article a
                     WHERE a.id_art = :art_id";
        }

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':art_id', $article_id, PDO::PARAM_INT);
        $stmt->execute();

        // récupère le résultat
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$article) {
            return json_encode(['error' => 'Article non trouvé']);
        }

        // ajoute le statut favori
        $article['is_favorite'] = is_favorite($article_id);

        // formate la date
        $date = strtotime($article['date_art']);
        $article['formatted_date'] = date('d/m/Y', $date);

        // renvoye des données
        return json_encode($article);

    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération de l'article: " . $e->getMessage());
        return json_encode(['error' => 'Erreur de base de données']);
    }
}