<?php

function main_static():string
{
    $name = $_GET['name'] ?? '404';
    $html_body = get_static_content($name);
    return join( "\n", [// permet la concaténation des chaines de caractère avec comme séparateur le saut à la ligne
		ctrl_head(),
		$html_body,
		html_foot(),
	]);

}

