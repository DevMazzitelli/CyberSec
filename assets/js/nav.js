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