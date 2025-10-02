<?php

function html_home_main($articles)
{
    // récupère le rôle de l'utilisateur connecté
    $user_role = $_SESSION['role'] ?? '';

    ob_start();
    ?>
    <main>
        <div class="content-container">
            <section class="articles-section">
                <h2 class="section-title">On n'est pas des pigeons</h2>

                <div class="articles-grid">
                    <?php
                    // parcourir tous les articles un par un
                    foreach ($articles as $index => $article):
                        // si l'index est pair (0, 2, 4...), on va à la ligne
                        if ($index % 2 == 0) echo '<div class="articles-row">';
                        ?>

                        <div class="article-card" data-id="<?= $article['id_art'] ?>">
                            <!-- Conteneur pour l'image -->
                            <div class="article-image-container">
                                <?php if (!empty($article['image_art'])): ?>
                                    <img src="../public/media_article/<?= htmlspecialchars($article['image_art']) ?>"
                                         alt="<?= htmlspecialchars($article['title_art']) ?>" class="article-image">
                                <?php else: ?>
                                    <div class="article-image"><img src="../public/media/pigeon2.png"
                                                                    alt="image alternative"></div>
                                <?php endif; ?>
                            </div>

                            <!-- titre de l'article uniquement -->
                            <h3 class="article-title">
                                <a href="?page=article&art_id=<?= $article['id_art'] ?>">
                                    <?= htmlspecialchars($article['title_art']) ?>
                                </a>
                            </h3>

                            <!-- bouton favoris avec support AJAX/MPA -->
                            <div class="article-footer">
                                <?php if (AJAX_ENABLED): ?>
                                    <!-- Version AJAX simple -->
                                    <?php if (is_favorite($article['id_art'])): ?>
                                        <button class="btn btn-success btn-sm favorite-ajax-btn"
                                                data-article-id="<?= $article['id_art'] ?>"
                                                data-action="remove">
                                            ★ Favori
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-outline-primary btn-sm favorite-ajax-btn"
                                                data-article-id="<?= $article['id_art'] ?>"
                                                data-action="add">
                                            ☆ Ajouter
                                        </button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <!-- version classique -->
                                    <?php if (is_favorite($article['id_art'])): ?>
                                        <a href="?page=favorite&action=remove&article_id=<?= $article['id_art'] ?>&return=home"
                                           class="btn btn-success btn-sm">★ Favori</a>
                                    <?php else: ?>
                                        <a href="?page=favorite&action=add&article_id=<?= $article['id_art'] ?>&return=home"
                                           class="btn btn-outline-primary btn-sm">☆ Ajouter</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php
                        if ($index % 2 == 1 || $index == count($articles) - 1) echo '</div>';
                        ?>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- section d'aperçu de l'article à droite -->
            <div id="article-preview">
                <h3 class="preview-title">Détails de l'article</h3>
                <div class="preview-content">
                    <p class="preview-notice">Survolez un article pour voir plus de détails</p>
                    <div class="preview-details" style="display: none;">
                        <div class="preview-image-container">
                            <img id="preview-image" src="" alt="Image de l'article">
                        </div>
                        <h4 id="preview-title"></h4>
                        <div id="preview-content"></div>

                        <div class="preview-footer">
                            <span id="preview-date"></span>
                            <a id="preview-link" href="#" class="btn btn-primary btn-sm">Lire l'article</a>
                        </div>

                        <!-- informations supplémentaires pour l'admin -->
                        <?php if ($user_role === 'Super Administrateur' || $user_role === 'administrateur'): ?>
                            <div class="admin-details">
                                <div>Catégorie: <span id="preview-category"></span></div>
                                <div>Temps de lecture: <span id="preview-readtime"></span></div>
                                <div>ID: <span id="preview-id"></span></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php
    return ob_get_clean();
}

/**
 * affiche un message demandant à l'utilisateur de se connecter
 * @return string HTML généré
 */
function html_login_required()
{
    ob_start();
    ?>
    <div class="login-required-container">
        <div class="login-message">
            <h2>Accès restreint</h2>
            <p>Pour afficher nos différents articles, vous devez vous connecter.</p>
            <a href="?page=login" class="btn btn-primary">Se connecter</a>
        </div>
    </div>
    <?php
    return ob_get_clean();
}