/**
 * Gestion simple des favoris en AJAX
 */
$(document).ready(function() {

    // quand on clique sur un bouton favori AJAX
    $(document).on('click', '.favorite-ajax-btn', function() {
        var button = $(this);
        var articleId = button.data('article-id');
        var action = button.data('action'); // 'add' ou 'remove'

        //désactive le bouton pendant la requête
        button.prop('disabled', true);

        // envoie la requête AJAX
        $.post('index.php', {
            page: 'favorite_ajax',
            action: action,
            article_id: articleId
        }, function(response) {

            if (response.success) {
                // succès : changer le bouton
                if (response.action === 'added') {
                    button.removeClass('btn-outline-primary')
                        .addClass('btn-success')
                        .html('★ Favori')
                        .data('action', 'remove');
                } else if (response.action === 'removed') {
                    button.removeClass('btn-success')
                        .addClass('btn-outline-primary')
                        .html('☆ Ajouter')
                        .data('action', 'add');
                }

                // affiche un message (optionnel)
                showMessage('Favori mis à jour !', 'success');

            } else {
                // erreur : afficher message
                showMessage(response.error, 'error');
            }

        }, 'json').always(function() {
            // réactive le bouton
            button.prop('disabled', false);
        });
    });

    // bouton "Vider tous les favoris"
    $(document).on('click', '#clear-all-favorites', function() {
        if (!confirm('Supprimer tous vos favoris ?')) return;

        $.post('index.php', {
            page: 'favorite_ajax',
            action: 'clear'
        }, function(response) {

            if (response.success) {
                // Recharger la page pour voir les changements
                location.reload();
            }

        }, 'json');
    });

    // fonction simple pour afficher des messages
    function showMessage(text, type) {
        var className = (type === 'success') ? 'alert-success' : 'alert-danger';
        var message = '<div class="alert ' + className + ' alert-dismissible position-fixed" ' +
            'style="top: 100px; right: 20px; z-index: 9999;">' +
            text +
            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
            '</div>';

        $('body').append(message);

        // supprime après 3 secondes
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    }
});