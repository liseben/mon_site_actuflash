/**
 * Menu dynamique AJAX
 */
$(document).ready(function() {
    // vérifie si on est en mode AJAX (chercher l'élément du menu dynamique)
    if ($('#dynamic-menu').length && $('#dynamic-menu').html().includes('Chargement...')) {

        // charge le menu via AJAX
        $.post('index.php', {
            page: 'menu_ajax'
        }, function(response) {

            if (response.success && response.menu) {
                var menuHtml = '';

                // Construit le HTML du menu
                response.menu.forEach(function(item) {
                    var option = item.option ? '&name=' + item.option : '';
                    menuHtml += '<li class="nav-item">' +
                        '<a class="nav-link" href="?page=' + item.link + option + '">' +
                        item.text + '</a></li>';
                });

                // remplace le contenu du menu
                $('#dynamic-menu').html(menuHtml);

            } else {
                // en cas d'erreur, afficher un menu minimal
                $('#dynamic-menu').html(
                    '<li class="nav-item"><a class="nav-link" href="?page=home">Accueil</a></li>' +
                    '<li class="nav-item"><a class="nav-link" href="?page=login">Connexion</a></li>'
                );
            }

        }, 'json').fail(function() {
            // fallback en cas d'échec total(fallback : si l'AJAX échoue, un menu minimal s'affiche quand même)
            $('#dynamic-menu').html(
                '<li class="nav-item"><a class="nav-link" href="?page=home">Accueil</a></li>'
            );
        });
    }
});