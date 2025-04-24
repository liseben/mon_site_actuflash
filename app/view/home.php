<?php

/**
 * build <body>
 * @param $user
 * @param $role
 */
function html_body($article_a)
{
    $title = $article_a['title'];
    $hook = $article_a['hook'];

	ob_start();
	?>
    <section class="breaking">
        <article>
            <h1><?=$title?></h1>
            <h2><?=$hook?></h2>
        </article>
    </section>
    <section class="other">
        <article>
            Autres articles...
        </article>
    </section>


    <?php
	return ob_get_clean();
}

