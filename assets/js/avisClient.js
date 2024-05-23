function avisprecedent(numero) { /* bouton commentaire precedent */
    const avisapres = document.getElementById("Avis"+ numero);     /* const */
    const avisavant = document.getElementById("Avis"+ (numero - 1)); /* const */

    if ((numero - 1) === 0) { /* Permet d'afficher l'Avis 4 si on est a l'avis 1 */
        const avisder = document.getElementById("Avis4")

        avisapres.style.display = "none"
        avisder.style.display = "block"

    }
    else { /* Permet de passer a l'avis précedent */
        if(getComputedStyle(avisavant).display == "none"){
            avisavant.style.display = "block";
            avisapres.style.display = "none";
        }
    }
}

function avissuivant(numero) { /* bouton commentaire suivant */
    const avisavant = document.getElementById("Avis"+ (numero - 1)); /* const */
    const avisapres = document.getElementById("Avis"+ numero); /* const */
    const aviss4 = document.getElementById("Avis4")
    if (numero === 1) { /* Permet d'afficher l'Avis 1 si on est a l'avis 4 */
        const avisder = document.getElementById("Avis1")
        avisapres.style.display = "none"
        avisder.style.display = "block"
        aviss4.style.display = "none"
    }
    else { /* Permet de passer a l'avis d'après */
        if (getComputedStyle(avisapres).display == "none"){
            avisapres.style.display = "block";
            avisavant.style.display = "none";
        }

    }
}