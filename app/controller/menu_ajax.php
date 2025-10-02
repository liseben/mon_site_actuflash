<?php
/**
 * Contrôleur AJAX pour le menu dynamique
 */
function main_menu_ajax() {
    $menu_items = get_menu_csv();
    $user_role = $_SESSION['role'] ?? '';

    // menu de base pour tous
    $filtered_menu = [];

    foreach ($menu_items as $item) {
        if (isset($item[0]) && isset($item[1])) {
            $filtered_menu[] = [
                'text' => $item[0],
                'link' => $item[1],
                'option' => isset($item[2]) ? $item[2] : ''
            ];
        }
    }

    // retourne le menu au format JSON
    return json_encode([
        'success' => true,
        'menu' => $filtered_menu,
        'user_role' => $user_role
    ]);
}