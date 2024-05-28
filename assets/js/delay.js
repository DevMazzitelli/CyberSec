$(document).ready(function() {
    // Faire disparaître l'alerte automatiquement après 3 secondes
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 3000); // 3000ms = 3s
});