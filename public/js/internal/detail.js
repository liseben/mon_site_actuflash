/**
 * detail.js
 * Gestion de l'affichage des détails d'articles au survol avec AJAX
 */
$(document).ready(function() {
    console.log('Script detail.js chargé');

    // vérifie si les éléments existent
    if ($('.article-card').length === 0 || $('#article-preview').length === 0) {
        console.log('Les éléments nécessaires pour l\'aperçu ne sont pas présents');
        return;
    }

    // sélectionne les éléments
    var $articles = $('.article-card');
    var $previewNotice = $('.preview-notice');
    var $previewDetails = $('.preview-details');
    var $previewImage = $('#preview-image');
    var $previewTitle = $('#preview-title');
    var $previewContent = $('#preview-content');
    var $previewDate = $('#preview-date');
    var $previewLink = $('#preview-link');

    // élément pour l'admin
    var $previewCategory = $('#preview-category');
    var $previewReadtime = $('#preview-readtime');
    var $previewId = $('#preview-id');

    // variable pour l'article actuel
    var currentArticleId = null;

    // fonction pour charger les détails d'un article
    function loadArticleDetails(articleId) {
        console.log('Chargement des détails pour l\'article ID:', articleId);

        // si c'est déjà cet article qui est affiché, ne rien faire
        if (currentArticleId === articleId) {
            return;
        }

        // mettre à jour l'article courant
        currentArticleId = articleId;

        // afficher un indicateur de chargement
        $previewDetails.css('opacity', '0.5');

        // requête AJAX
        $.ajax({
            url: 'index.php',
            type: 'GET',
            data: {
                page: 'detail_ajax',
                art_id: articleId
            },
            dataType: 'json',
            success: function(data) {
                console.log('Données reçues:', data);
                updatePreviewContent(data);
            },
            error: function(xhr, status, error) {
                console.error('Erreur AJAX:', status, error);
                console.log('Réponse:', xhr.responseText);

                // Afficher un message d'erreur
                $previewContent.html('<p class="error">Erreur lors du chargement des détails.</p>');
                $previewDetails.css('opacity', '1');
            }
        });
    }

    // fonction pour mettre à jour l'aperçu
    function updatePreviewContent(article) {
        // définir le chemin de l'image
        var imagePath = article.image_art
            ? '../public/media_article/' + article.image_art
            : '../public/media/pigeon2.png';

        // mettre à jour les éléments de base (pour tous les utilisateurs)
        $previewImage.attr('src', imagePath);
        $previewImage.attr('alt', article.title_art);
        $previewTitle.text(article.title_art);

        // crée un extrait du contenu (premiers 150 caractères)
        var contentExcerpt = '';
        if (article.content_art) {
            // supprime les balises HTML
            var tempDiv = document.createElement('div');
            tempDiv.innerHTML = article.content_art;
            var textContent = tempDiv.textContent || tempDiv.innerText || '';

            // crée un extrait
            contentExcerpt = textContent.substring(0, 150) + '...';
        }

        $previewContent.text(contentExcerpt);
        $previewDate.text(article.formatted_date || article.date_art);
        $previewLink.attr('href', '?page=article&art_id=' + article.id_art);

        // informations supplémentaires pour l'admin
        if ($previewCategory.length) {
            $previewCategory.text(article.category_name || article.fk_category_art || 'N/A');
            $previewReadtime.text(article.readtime_art || 'N/A');
            $previewId.text(article.id_art || article.ident_art || 'N/A');
        }

        // affiche les détails et masquer le message
        $previewNotice.hide();
        $previewDetails.show().css('opacity', '1');

        // mettre en évidence l'article actif
        $articles.removeClass('active');
        $('.article-card[data-id="' + article.id_art + '"]').addClass('active');

        // si l'utilisateur est admin et que les éléments existent
        if ($('#preview-category').length && $('#preview-readtime').length && $('#preview-id').length) {
            // ajouter les informations supplémentaires
            $('#preview-category').text(article.category_name || article.fk_category_art || 'Non spécifiée');
            $('#preview-readtime').text(article.readtime_art || 'Non spécifié');
            $('#preview-id').text(article.ident_art || article.id_art || '');

            // affiche le conteneur
            $('.admin-details').show();
        } else {
            // cache le conteneur si l'utilisateur n'est pas admin
            $('.admin-details').hide();
        }
    }

    // ajoute l'événement de survol
    $articles.hover(
        function() {
            var articleId = $(this).data('id');
            loadArticleDetails(articleId);
        },
        function() {
            // ne rien faire quand la souris quitte l'article
        }
    );

    // charger le premier article par défaut
    if ($articles.length > 0) {
        var firstArticleId = $articles.first().data('id');
        loadArticleDetails(firstArticleId);
    }
});