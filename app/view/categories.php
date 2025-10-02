<?php
/**
 * affiche la liste des catégories
 * @param array $categories Liste des catégories
 * @return string HTML généré
 */
function html_categories($categories)
{
    ob_start();
    ?>
    <div class="container">
        <h2 class="section-title">Catégories</h2>

        <div class="category-grid">
            <?php
            // compteur pour savoir quand commencer une nouvelle ligne
            $count = 0;

            // ouvrir la première ligne
            echo '<div class="category-row">';

            // parcourir toutes les catégories
            foreach($categories as $category):
                ?>
                <div class="category-item">
                    <a href="?page=categories&category_id=<?= $category['id_cat'] ?>">
                        <?= $category['name_cat'] ?>
                    </a>
                </div>
                <?php

                $count++;

                // si nous avons 3 catégories et qu'il y a encore des catégories à afficher
                if ($count % 3 == 0 && $count < count($categories)) {
                    // Fermer la ligne actuelle et en ouvrir une nouvelle
                    echo '</div><div class="category-row">';
                }
            endforeach;

            // fermeture de la dernière ligne
            echo '</div>';
            ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * affiche les articles d'une catégorie spécifique
 * @param string $category_name Nom de la catégorie
 * @param array $articles Articles de la catégorie
 * @param int $category_id ID de la catégorie (pour les liens de favoris)
 * @return string HTML généré
 */
function html_category_articles($category_name, $articles, $category_id) {
    ob_start();
    ?>
    <div class="container">
        <h2 class="section-title">Articles dans la catégorie "<?= $category_name ?>"</h2>

        <?php if (empty($articles)): ?>
            <p class="no-articles">Aucun article trouvé dans cette catégorie.</p>
        <?php else: ?>
            <div class="articles-grid">
                <?php
                foreach ($articles as $index => $article):
                    // si l'index est pair (0, 2, 4...), on commence une nouvelle ligne
                    if ($index % 2 == 0) echo '<div class="articles-row">';
                    ?>
                    <div class="article-card">
                        <!-- conteneur pour l'image -->
                        <div class="article-image-container">
                            <?php if (!empty($article['image_art'])): ?>
                                <img src="../public/media_article/<?= $article['image_art'] ?>"
                                     alt="<?= $article['title_art'] ?>" class="article-image">
                            <?php else: ?>
                                <div class="article-image">
                                    <img src="../public/media/pigeon2.png" alt="image alternative">
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- titre de l'article -->
                        <h3 class="article-title">
                            <a href="?page=article&art_id=<?= $article['id_art'] ?>">
                                <?= $article['title_art'] ?>
                            </a>
                        </h3>

                        <!-- résumé de l'article (si disponible) -->
                        <?php if (!empty($article['hook_art'])): ?>
                            <p class="article-hook"><?= $article['hook_art'] ?></p>
                        <?php endif; ?>

                        <!-- pied de l'article avec date et bouton favoris -->
                        <div class="article-footer">
                            <span class="article-date">
                                <?= !empty($article['date_art']) ? date('d/m/Y', strtotime($article['date_art'])) : '' ?>
                            </span>

                            <!-- bouton favoris modifié pour rester sur la même page -->
                            <?php if (AJAX_ENABLED): ?>
                                <!-- Version AJAX -->
                                <?php if (function_exists('is_favorite') && is_favorite($article['id_art'])): ?>
                                    <button class="favorite-btn favorite-ajax-btn active"
                                            data-article-id="<?= $article['id_art'] ?>"
                                            data-action="remove">
                                        ★ Favoris
                                    </button>
                                <?php else: ?>
                                    <button class="favorite-btn favorite-ajax-btn"
                                            data-article-id="<?= $article['id_art'] ?>"
                                            data-action="add">
                                        ☆ Favoris
                                    </button>
                                <?php endif; ?>
                            <?php else: ?>
                                <!-- Version classique MPA -->
                                <?php if (function_exists('is_favorite') && is_favorite($article['id_art'])): ?>
                                    <a href="?page=categories&category_id=<?= $category_id ?>&action=remove_favorite&article_id=<?= $article['id_art'] ?>"
                                       class="favorite-btn active">★ Favoris</a>
                                <?php else: ?>
                                    <a href="?page=categories&category_id=<?= $category_id ?>&action=add_favorite&article_id=<?= $article['id_art'] ?>"
                                       class="favorite-btn">☆ Favoris</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php
                    // si l'index est impair (1, 3, 5...) ou c'est le dernier article, on ferme la ligne
                    if ($index % 2 == 1 || $index == count($articles) - 1) echo '</div>';
                endforeach;
                ?>
            </div>
        <?php endif; ?>

        <!-- lien de retour vers les catégories -->
        <div class="back-link">
            <a href="?page=categories" class="btn btn-secondary">Retour aux catégories</a>
        </div>
    </div>
    <?php
    return ob_get_clean();
}