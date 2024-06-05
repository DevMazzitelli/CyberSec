document.addEventListener('DOMContentLoaded', function () {
    const previous = document.getElementById('previous');
    const next = document.getElementById('next');
    let currentPage = getPageNumberFromUrl();

    function getPageNumberFromUrl() {
        const match = window.location.pathname.match(/\/(\d+)$/); // Récupère le numéro de page depuis l'URL
        return match ? parseInt(match[1]) : 1; // Si aucun numéro de page n'est trouvé, retourne 1 par défaut
    }

    previous.addEventListener('click', function (event) {
        event.preventDefault();
        if (currentPage > 1) {
            currentPage--;
            updateUrl(currentPage);
        }
    });

    next.addEventListener('click', function (event) {
        event.preventDefault();
        currentPage++;
        updateUrl(currentPage);
    });

    function updateUrl(pageNumber) {
        const baseUrl = window.location.pathname.replace(/\/(\d+)$/, ''); // Retire le numéro de page actuel de l'URL
        const newUrl = pageNumber === 1 ? baseUrl : baseUrl + '/' + pageNumber; // Ajoute le nouveau numéro de page à l'URL (sauf si c'est la première page)
        window.location.href = newUrl; // Redirige vers la nouvelle URL
    }
});
