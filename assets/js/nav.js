window.addEventListener('scroll', function() {
    var navUn = document.getElementById('navUn');
    var navDeux = document.getElementById('navDeux');
    var scrollPosition = window.scrollY;

    if (scrollPosition > 0) {
        navUn.classList.add('hidden');
        navDeux.classList.remove('hidden');
    } else {
        navUn.classList.remove('hidden');
        navDeux.classList.add('hidden');
    }
});

window.addEventListener('scroll', function() {
    var div = document.getElementById('myDiv');
    if (window.pageYOffset > 0) {
        // Si l'utilisateur a défilé vers le bas, masquez la div
        div.style.display = 'none';
    } else {
        // Si l'utilisateur est revenu en haut, affichez la div
        div.style.display = 'block';
    }
});
// Burger menus
document.addEventListener('DOMContentLoaded', function() {
    // open
    const burger = document.querySelectorAll('.navbar-burger');
    const menu = document.querySelectorAll('.navbar-menu');

    if (burger.length && menu.length) {
        for (var i = 0; i < burger.length; i++) {
            burger[i].addEventListener('click', function() {
                for (var j = 0; j < menu.length; j++) {
                    menu[j].classList.toggle('hidden');
                }
            });
        }
    }

    // close
    const close = document.querySelectorAll('.navbar-close');
    const backdrop = document.querySelectorAll('.navbar-backdrop');

    if (close.length) {
        for (var i = 0; i < close.length; i++) {
            close[i].addEventListener('click', function() {
                for (var j = 0; j < menu.length; j++) {
                    menu[j].classList.toggle('hidden');
                }
            });
        }
    }

    if (backdrop.length) {
        for (var i = 0; i < backdrop.length; i++) {
            backdrop[i].addEventListener('click', function() {
                for (var j = 0; j < menu.length; j++) {
                    menu[j].classList.toggle('hidden');
                }
            });
        }
    }
});