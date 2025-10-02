<?php
/**
 * Affiche le formulaire de recherche par critères avec dates (version menu latéral)
 * @param string $keyword Mot-clé (si déjà défini)
 * @param int $readtime_min Temps de lecture minimum (si déjà défini)
 * @param int $readtime_max Temps de lecture maximum (si déjà défini)
 * @param int|null $category_id ID de la catégorie sélectionnée (si déjà définie)
 * @param string $date_min Date minimum (si déjà définie)
 * @param string $date_max Date maximum (si déjà définie)
 * @param array $categories Liste des catégories disponibles
 * @param string $error Message d'erreur (si applicable)
 * @return string HTML généré
 */
function html_search_form($keyword = '', $readtime_min = 0, $readtime_max = 60, $category_id = null, $date_min = '', $date_max = '', $categories = [], $error = '') {
    ob_start();
    ?>
    <div class="search-sidebar">
        <h3 class="sidebar-title">Critères de recherche</h3>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <!-- Champ de recherche par mot-clé -->
        <div class="sidebar-group">
            <label for="keyword">Mot-clé</label>
            <input type="text" id="keyword" name="keyword" class="sidebar-input"
                   placeholder="Entrez un mot-clé" value="<?= htmlspecialchars($keyword) ?>">
        </div>

        <!-- champs de recherche par date -->
        <div class="sidebar-group">
            <label>Période de publication</label>
            <div class="date-inputs">
                <div class="date-input">
                    <label for="date_min">Du :</label>
                    <input type="date" id="date_min" name="date_min" class="sidebar-input"
                           value="<?= htmlspecialchars($date_min) ?>">
                </div>
                <div class="date-input">
                    <label for="date_max">Au :</label>
                    <input type="date" id="date_max" name="date_max" class="sidebar-input"
                           value="<?= htmlspecialchars($date_max) ?>">
                </div>
            </div>
        </div>

        <!-- slider pour le temps de lecture -->
        <div class="sidebar-group">
            <label>Temps de lecture (minutes)</label>
            <div class="readtime-inputs">
                <div class="readtime-input">
                    <label for="readtime_min">Min:</label>
                    <input type="number" id="readtime_min" name="readtime_min" class="sidebar-input"
                           min="0" max="120" value="<?= $readtime_min ?>">
                    <input type="range" id="readtime_min_slider" min="0" max="120"
                           value="<?= $readtime_min ?>" class="sidebar-range">
                </div>

                <div class="readtime-input">
                    <label for="readtime_max">Max:</label>
                    <input type="number" id="readtime_max" name="readtime_max" class="sidebar-input"
                           min="0" max="120" value="<?= $readtime_max ?>">
                    <input type="range" id="readtime_max_slider" min="0" max="120"
                           value="<?= $readtime_max ?>" class="sidebar-range">
                </div>
            </div>
        </div>

        <!-- menu déroulant pour les catégories -->
        <div class="sidebar-group">
            <label for="category_id">Catégorie</label>
            <select id="category_id" name="category_id" class="sidebar-select">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id_cat'] ?>" <?= $category_id == $category['id_cat'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name_cat']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>


    </div>

    <!-- script pour la gestion des sliders -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const minSlider = document.getElementById('readtime_min_slider');
            const maxSlider = document.getElementById('readtime_max_slider');
            const minInput = document.getElementById('readtime_min');
            const maxInput = document.getElementById('readtime_max');

            // synchronise le slider avec l'input number (min)
            minSlider.addEventListener('input', function() {
                minInput.value = this.value;
                if (parseInt(minInput.value) > parseInt(maxInput.value)) {
                    maxInput.value = minInput.value;
                    maxSlider.value = minInput.value;
                }
            });

            // synchronise l'input number avec le slider (min)
            minInput.addEventListener('input', function() {
                minSlider.value = this.value;
                if (parseInt(minInput.value) > parseInt(maxInput.value)) {
                    maxInput.value = minInput.value;
                    maxSlider.value = minInput.value;
                }
            });

            // synchronise le slider avec l'input number (max)
            maxSlider.addEventListener('input', function() {
                maxInput.value = this.value;
                if (parseInt(maxInput.value) < parseInt(minInput.value)) {
                    minInput.value = maxInput.value;
                    minSlider.value = maxInput.value;
                }
            });

            // synchronise l'input number avec le slider (max)
            maxInput.addEventListener('input', function() {
                maxSlider.value = this.value;
                if (parseInt(maxInput.value) < parseInt(minInput.value)) {
                    minInput.value = maxInput.value;
                    minSlider.value = maxInput.value;
                }
            });

            // validation des dates
            const dateMin = document.getElementById('date_min');
            const dateMax = document.getElementById('date_max');

            function validateDates() {
                if (dateMin.value && dateMax.value) {
                    if (new Date(dateMin.value) > new Date(dateMax.value)) {
                        // échange les valeurs si date_min > date_max
                        const temp = dateMin.value;
                        dateMin.value = dateMax.value;
                        dateMax.value = temp;
                    }
                }
            }

            dateMin.addEventListener('change', validateDates);
            dateMax.addEventListener('change', validateDates);
        });
    </script>
    <?php
    return ob_get_clean();
}

/**
 * Affiche les résultats de la recherche avec dates (version contenu principal)
 * @param array $articles Articles trouvés
 * @param string $keyword Mot-clé utilisé pour la recherche
 * @param int $readtime_min Temps de lecture minimum utilisé pour la recherche
 * @param int $readtime_max Temps de lecture maximum utilisé pour la recherche
 * @param int|null $category_id ID de la catégorie utilisée pour la recherche
 * @param string $date_min Date minimum utilisée pour la recherche
 * @param string $date_max Date maximum utilisée pour la recherche
 * @param array $categories Liste des catégories (pour afficher le nom)
 * @return string HTML généré
 */
function html_search_results($articles, $keyword = '', $readtime_min = 0, $readtime_max = 60, $category_id = null, $date_min = '', $date_max = '', $categories = []) {
    ob_start();
    ?>
    <div class="search-main-content">
        <h2 class="main-title">Résultats de la recherche</h2>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <div class="search-criteria">
                <strong>Critères de recherche :</strong>
                <?php if (!empty($keyword)): ?>
                    <span class="criteria-item">Mot-clé: "<?= htmlspecialchars($keyword) ?>"</span>
                <?php endif; ?>
                <span class="criteria-item">Temps: <?= $readtime_min ?> à <?= $readtime_max ?> min</span>
                <?php if (!empty($date_min) || !empty($date_max)): ?>
                    <span class="criteria-item">
                        Période:
                        <?= !empty($date_min) ? date('d/m/Y', strtotime($date_min)) : 'Début' ?>
                        au
                        <?= !empty($date_max) ? date('d/m/Y', strtotime($date_max)) : 'Aujourd\'hui' ?>
                    </span>
                <?php endif; ?>
                <?php if (!empty($category_id)):
                    $category_name = 'Inconnue';
                    foreach ($categories as $cat) {
                        if ($cat['id_cat'] == $category_id) {
                            $category_name = $cat['name_cat'];
                            break;
                        }
                    }
                    ?>
                    <span class="criteria-item">Catégorie: <?= htmlspecialchars($category_name) ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($articles)): ?>
            <div class="no-results">
                <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                    <p>Aucun article ne correspond à ces critères.</p>
                    <p>Essayez de modifier vos critères de recherche.</p>
                <?php else: ?>
                    <p>Utilisez le formulaire ci-contre pour rechercher des articles.</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="search-results-grid">
                <?php foreach ($articles as $article): ?>
                    <div class="search-result-card">
                        <!-- image de l'article -->
                        <div class="result-image-container">
                            <a href="?page=article&art_id=<?= $article['id_art'] ?>">
                                <?php if (!empty($article['image_art'])): ?>
                                    <img src="../public/media_article/<?= htmlspecialchars($article['image_art']) ?>"
                                         alt="<?= htmlspecialchars($article['title_art']) ?>" class="result-image">
                                <?php else: ?>
                                    <img src="../public/media/pigeon2.png" alt="image alternative" class="result-image">
                                <?php endif; ?>
                            </a>
                        </div>

                        <!-- contenu de l'article -->
                        <div class="result-content">
                            <h3 class="result-title">
                                <a href="?page=article&art_id=<?= $article['id_art'] ?>">
                                    <?= htmlspecialchars($article['title_art']) ?>
                                </a>
                            </h3>

                            <p class="result-hook"><?= htmlspecialchars($article['hook_art']) ?></p>

                            <div class="result-meta">
                                <span class="result-date">
                                    <?= date('d/m/Y', strtotime($article['date_art'])) ?>
                                </span>
                                <span class="result-readtime">
                                    <?= $article['readtime_art'] ?? 'Non spécifié' ?> min
                                </span>
                            </div>

                            <!-- bouton favoris AJAX/MPA -->
                            <div class="result-footer">
                                <?php if (AJAX_ENABLED): ?>
                                    <!-- Version AJAX simple -->
                                    <?php if (function_exists('is_favorite') && is_favorite($article['id_art'])): ?>
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
                                    <!-- version classique MPA -->
                                    <?php if (function_exists('is_favorite') && is_favorite($article['id_art'])): ?>
                                        <a href="?page=favorite&action=remove&article_id=<?= $article['id_art'] ?>&return=search"
                                           class="btn btn-success btn-sm">★ Favori</a>
                                    <?php else: ?>
                                        <a href="?page=favorite&action=add&article_id=<?= $article['id_art'] ?>&return=search"
                                           class="btn btn-outline-primary btn-sm">☆ Ajouter</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}