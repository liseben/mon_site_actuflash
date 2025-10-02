/** gestion des préférences d'affichage avec JavaScript et localStorage */


document.addEventListener('DOMContentLoaded', function() {
    // sélecteurs pour les menus déroulants
    const themeSelector = document.getElementById('background');
    const fontSizeSelector = document.getElementById('fontsize');
    const colorThemes = ['yellow', 'rose', 'blue']; // Nouveaux thèmes de couleur
    const fontSizes = ['small', 'medium', 'large']; // Tailles de police

    // applique les préférences sauvegardées lors du chargement de la page
    applyStoredPreferences();

    // évènements pour les changements de sélection
    if (themeSelector) {
        themeSelector.addEventListener('change', function() {
            const selectedTheme = this.value;
            setTheme(selectedTheme);
        });
    }

    if (fontSizeSelector) {
        fontSizeSelector.addEventListener('change', function() {
            const selectedFontSize = this.value;
            setFontSize(selectedFontSize);
        });
    }

    /**
     * applique les préférences stockées dans localStorage
     */
    function applyStoredPreferences() {
        // récupère les préférences (ou utiliser les valeurs par défaut)
        const savedTheme = localStorage.getItem('userTheme') || 'yellow';
        const savedFontSize = localStorage.getItem('userFontSize') || 'medium';

        // applique les préférences
        setTheme(savedTheme, false);
        setFontSize(savedFontSize, false);

        // mise à jour les sélecteurs
        if (themeSelector) themeSelector.value = savedTheme;
        if (fontSizeSelector) fontSizeSelector.value = savedFontSize;
    }

    /**
     * définit le thème de couleur
     * @param {string} theme - Le thème à appliquer
     * @param {boolean} save - Si true, sauvegarde le thème dans localStorage
     */
    function setTheme(theme, save = true) {
        // validation du thème
        if (!colorThemes.includes(theme)) {
            theme = 'yellow'; // Thème par défaut
        }

        const body = document.body;

        // supprime toutes les classes de thème existantes
        colorThemes.forEach(t => body.classList.remove(t));

        // ajoute la nouvelle classe de thème
        body.classList.add(theme);

        // sauvegarde la préférence
        if (save) {
            localStorage.setItem('userTheme', theme);
        }
    }

    /**
     * définit la taille de police
     * @param {string} fontSize - La taille de police à appliquer
     * @param {boolean} save - Si true, sauvegarde la taille dans localStorage
     */
    function setFontSize(fontSize, save = true) {
        // validation de la taille de police
        if (!fontSizes.includes(fontSize)) {
            fontSize = 'medium'; // Taille par défaut
        }

        const body = document.body;

        // supprime toutes les classes de taille de police existantes
        fontSizes.forEach(size => body.classList.remove('fontsize-' + size));

        // ajoute la nouvelle classe de taille de police
        body.classList.add('fontsize-' + fontSize);

        // sauvegarde la préférence
        if (save) {
            localStorage.setItem('userFontSize', fontSize);
        }
    }
});