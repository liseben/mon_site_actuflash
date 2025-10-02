<?php
function html_article_main($article_a)
{
    // récupérer le rôle de l'utilisateur
    $user_role = $_SESSION['role'] ?? '';

    // extraire correctement l'ID
    $article_id = $article_a['id_art'] ?? $article_a['id'] ?? 0;

    // déboguer pour vérifier les données reçues
    error_log("Données de l'article reçues : " . print_r($article_a, true));

    $title = $article_a['title_art'] ?? $article_a['title'];
    $hook = $article_a['hook_art'] ?? $article_a['hook'];
    $contents = $article_a['content_art'] ?? $article_a['contents'];
    $date = $article_a['date_art'] ?? $article_a['date_published'];

    // gérer plusieurs formats possibles pour l'image
    $image = '';
    if (!empty($article_a['image_art'])) {
        $image = $article_a['image_art'];
    } elseif (!empty($article_a['image'])) {
        $image = $article_a['image'];
    }

    // informations supplémentaires pour l'admin
    $category = isset($article_a['category_name']) ? $article_a['category_name'] : ($article_a['fk_category_art'] ?? 'Non spécifiée');
    $readtime = $article_a['readtime_art'] ?? 'Non spécifié';
    $ident = $article_a['ident_art'] ?? $article_id;

    ob_start();
    ?>
    <div class="article-page-container">
        <section class="article">
            <article>
                <!-- titre de l'article -->
                <h1><?= $title ?></h1>

                <!-- accroche de l'article -->
                <h2><?= $hook ?></h2>

                <!-- date de publication -->
                <div class="article-date"><?= $date ?></div>

                <!-- image de l'article -->
                <div class="article-image-full">
                    <?php if (!empty($image)): ?>
                        <img src="../public/media_article/<?= htmlspecialchars($image) ?>"
                             alt="<?= htmlspecialchars($title) ?>">
                    <?php else: ?>
                        <img src="../public/media/pigeon2.png" alt="Image par défaut">
                    <?php endif; ?>
                </div>

                <!-- contenu de l'article -->
                <div class="article-content" style="font-size: 16px; line-height: 1.6">
                    <?= $contents ?>
                </div>

                <!-- informations supplémentaires pour l'admin -->
                <?php if ($user_role === 'administrateur' || $user_role === 'Super administrateur'): ?>
                    <div class="article-admin-info">
                        <div><strong>Catégorie:</strong> <?= htmlspecialchars($category) ?></div>
                        <div><strong>Temps de lecture:</strong> <?= htmlspecialchars($readtime) ?></div>
                        <div><strong>ID:</strong> <?= htmlspecialchars($ident) ?></div>
                    </div>
                <?php endif; ?>

                <!-- boutons d'action -->
                <div class="article-actions">
                    <?php if (AJAX_ENABLED): ?>
                        <!-- Version AJAX -->
                        <?php if (is_favorite($article_id)): ?>
                            <button class="favorite-btn favorite-ajax-btn active"
                                    data-article-id="<?= $article_id ?>"
                                    data-action="remove">
                                ★ Retirer des favoris
                            </button>
                        <?php else: ?>
                            <button class="favorite-btn favorite-ajax-btn"
                                    data-article-id="<?= $article_id ?>"
                                    data-action="add">
                                ☆ Ajouter aux favoris
                            </button>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Version classique MPA -->
                        <?php if (is_favorite($article_id)): ?>
                            <a href="?page=favorite&action=remove&article_id=<?= $article_id ?>&return=article&art_id=<?= $article_id ?>"
                               class="favorite-btn active">
                                ★ Retirer des favoris
                            </a>
                        <?php else: ?>
                            <a href="?page=favorite&action=add&article_id=<?= $article_id ?>&return=article&art_id=<?= $article_id ?>"
                               class="favorite-btn">
                                ☆ Ajouter aux favoris
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <a href="?page=home" class="back-btn">
                        ← Retour à l'accueil
                    </a>
                </div>
            </article>
        </section>
    </div>
    <?php
    return ob_get_clean();
}