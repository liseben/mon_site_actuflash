<?php
/**
 * Contrôleur AJAX simple pour les favoris
 */
function main_favorite_ajax()
{
    // vérifie si l'utilisateur est connecté
    if (!isset($_SESSION['id'])) {
        echo json_encode(['success' => false, 'error' => 'Non connecté']);
        return;
    }

    $action = $_POST['action'] ?? '';
    $article_id = intval($_POST['article_id'] ?? 0);

    if ($action === 'add') {
        // vérifie la limite de 5
        $favorites = $_SESSION['favorites'] ?? [];
        if (count($favorites) >= 5) {
            echo json_encode(['success' => false, 'error' => 'Maximum 5 favoris autorisés']);
            return;
        }

        // ajoute aux favoris
        if (add_to_favorites($article_id)) {
            echo json_encode(['success' => true, 'action' => 'added']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Erreur ajout']);
        }
    }
    elseif ($action === 'remove') {
        // retire des favoris
        if (remove_from_favorites($article_id)) {
            echo json_encode(['success' => true, 'action' => 'removed']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Erreur suppression']);
        }
    }
    elseif ($action === 'clear') {
        // vide tous les favoris
        clear_favorites();
        echo json_encode(['success' => true, 'action' => 'cleared']);
    }
}