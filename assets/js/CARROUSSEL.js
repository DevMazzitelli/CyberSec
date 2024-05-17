$(document).ready(function(){
    $(".owl-carousel").owlCarousel({
        loop: true,
        autoplay: true,
        autoplayTimeout: 5000, // Toutes les 5 secondes, on change d'images
        responsive:{
            0:{
                items:1 // lorsque la largeur de l'écran est jusqu'à 600px, affichez 1 élément
            },
            /*
            750:{
                items:2
            }
            */
        }
    });
});


/*
    <section class="bg-fond">
        <div class="mx-auto w-1/2 px-4 py-15 sm:px-6 lg:px-8 lg:py-5">
            <div class="owl-carousel mt-8 grid grid-cols-1 gap-4 md:grid-cols-3">
                <img src="{{ asset('medias/images/Actu1.jpg') }}" class="w-screen">
                    <img src="{{ asset('medias/images/Actu3.jpg') }}" class="w-screen">
                        <img src="{{ asset('medias/images/Actu4.png') }}" class="w-screen">
            </div>
        </div>
    </section>
 */