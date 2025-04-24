<?php


function html_categories($categories, $selected_category, $articles)
{
    ob_start();
    ?>
    <div class="categories">
        <h1>Liste de Toutes les Catégories d'articles</h1>
        <div class="category-grid">
            <ol>
                <?php foreach($categories as $category): ?>
                    <div>
                        <li>
                            <a href="?page=categories&category=<?= $category['name_cat'] ?>">
                                <?= $category['name_cat'] ?>
                            </a>
                        </li>
                    </div>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>

    <?php if ($selected_category): ?>
    <h2>Articles de la catégorie "<?= $selected_category ?>"</h2>
    <div class="articles-grid">
        <?php foreach($articles as $article): ?>
            <div class="article-block">
                <img src="<?= $article['image_art'] ?>" alt="Article Image">
                <a href="?page=article&id=<?= $article['id_art'] ?>"><?= $article['title_art'] ?></a>
                <p><?= $article['date_art'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

    <?php
    return ob_get_clean();
}

