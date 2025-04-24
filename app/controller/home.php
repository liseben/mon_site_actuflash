<?php

function main_home():string
{
    $article_a = get_article_homepage(); //la fonction est crée dans le model

    return join( "\n", [// permet la concaténation des chaines de caractère avec comme séparateur le saut à la ligne
		ctrl_head(),
		html_body($article_a),
		html_foot(),
	]);

}

