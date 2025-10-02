<?php
/**
 * affiche la liste des articles favoris
 * @param array $favorite_articles Articles favoris
 * @return string HTML généré
 */
function html_favorites($favorite_articles)
{
    // calcule le nombre d'articles favoris(spec)
    $favorites_count = count($favorite_articles);
    ob_start();
    ?>
    <section class="pigeons-articles">
        <h2 class="section-title">Mes Articles Favoris
            <span class="favorites-count">( <?= $favorites_count ?> article(s) )</span>
        </h2>

        <?php if (empty($favorite_articles)): ?>
            <p class="no-favorites">Vous n'avez pas encore d'articles favoris.</p>
        <?php else: ?>
            <?php if (AJAX_ENABLED): ?>
                <!-- Version AJAX simple -->
                <button id="clear-all-favorites" class="btn btn-danger">
                    Vider tous mes favoris
                </button>
            <?php else: ?>
                <!-- Version classique -->
                <a href="?page=favorite&action=clear" class="btn btn-danger"
                   onclick="return confirm('Supprimer tous vos favoris ?')">
                    Vider tous mes favoris
                </a>
            <?php endif; ?>
            </div>

            <div class="articles-grid">
                <?php
                foreach ($favorite_articles as $index => $article):
                    if ($index % 2 == 0) echo '<div class="articles-row">';
                    ?>
                    <div class="article-card">
                        <!-- conteneur pour l'image -->
                        <div class="article-image-container">
                            <?php if (!empty($article['image_art'])): ?>
                                <img src="../public/media_article/<?= $article['image_art'] ?>"
                                     alt="<?= $article['title_art'] ?>" class="article-image">
                            <?php else: ?>
                                <img src="../public/media/pigeon2.png"
                                     alt="image alternative" class="article-image">
                            <?php endif; ?>
                        </div>

                        <!-- titre de l'article -->
                        <h3 class="article-title">
                            <a href="?page=article&art_id=<?= $article['id_art'] ?>">
                                <?= $article['title_art'] ?>
                            </a>
                        </h3>

                        <!-- résumé de l'article -->
                        <p class="article-hook"><?= $article['hook_art'] ?></p>

                        <!-- pied de l'article -->
                        <div class="article-footer">
                            <span class="article-date">
                                <?= date('d/m/Y', strtotime($article['date_art'])) ?>
                            </span>

                            <a href="?page=favorite&action=remove&article_id=<?= $article['id_art'] ?>&return=favorite"
                               class="favorite-btn active">
                                ★ Retirer
                            </a>
                        </div>
                    </div>
                    <?php
                    if ($index % 2 == 1 || $index == count($favorite_articles) - 1) echo '</div>';
                endforeach;
                ?>
            </div>
        <?php endif; ?>
    </section>
    <?php
    return ob_get_clean();
}