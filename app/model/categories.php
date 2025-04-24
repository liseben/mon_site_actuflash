<?php

//fonction qui récupère les toutes les catégories d'articles dans notre bd
function get_categories() {
    $dsn = "mysql:host=localhost;dbname=press_2024_v03;port=3307;charset=utf8mb4";
    $pdo = new PDO($dsn, "root", "");
    $q = <<<SQL
SELECT id_cat, name_cat FROM `t_category`
SQL;
    $stmt = $pdo->query($q); //dans l'instance de l'objet, on a la méthode query qui éxécutes des requetes
    return $stmt->fetchAll(PDO::FETCH_ASSOC); //le fetch_assoc qui récupère plusieurs colonnes au lieu d'une seule comme le fetch_column
}