<?php

function get_menu_csv()
{
    $fn = '../asset/database/menu.csv';
    $menu_s = file_get_contents($fn); // qui renvoie les élèments de $fn sous forme de chaine de caractères
    $menu_a = explode("\n", $menu_s);
    $menu_aa = [];
    foreach ($menu_a as $line)
    {
        $menu_aa[] = explode('|', $line);
    }
    return $menu_aa;
}