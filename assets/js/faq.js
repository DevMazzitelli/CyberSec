function rep(numero) {
    /* Récupération de l'élément avec l'ID correspondant */
    let t1 = document.getElementById("rep" + numero);
    // Vérification de la visibilité de l'élément
    if (window.getComputedStyle(t1).display === "none") {
        // Si l'élément est caché, le rendre visible
        t1.style.display = "block";
    } else {
        // Sinon, le cacher
        t1.style.display = "none";
    }

    // Toggle de l'icône SVG
    const icon = document.getElementById(`icon${numero}`).querySelector('svg');
    // Toggle the SVG icon
    if (icon.innerHTML.includes('M12 6v6m0 0v6m0-6h6m-6 0H6')) {
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6" />';
    } else {
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />';
    }
}

// Ajout d'un gestionnaire d'événements à chaque bouton .btnfaq
let btns = document.getElementsByClassName("btnfaq");
for (let i = 0; i < btns.length; i++) {
    btns[i].addEventListener('click', function() {
        // Appeler la fonction rep avec le numéro correspondant
        rep(i + 1);
    });
}

