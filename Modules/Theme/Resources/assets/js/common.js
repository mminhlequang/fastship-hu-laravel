// $(document).ready(function(){
//     $('#backtop').click(function () {
//         $('html,body').animate({scrollTop:0}, 500, 'swing');
//     });
//
// });
$(function() {
    //Featured
    $('.featured-slider').owlCarousel({
        items: 4,
        loop: true,
        lazyLoad: true,
        autoplay: true,
        autoplayTimeout: 4000,
        margin: 15,
        nav: true,
        responsiveClass: true,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 1,
                nav: false,
                dots: true,
            },
            576: {
                items: 2,
                nav: false,
                dots: true,
            },
            768: {
                items: 3,
                nav: false,
                dots: true,
                loop: false,
            },
            992: {
                items: 4,
                nav: true,
                loop: false,
            }
        }
    });
    //News Slider
    $('.news-other').owlCarousel({
        items: 3,
        loop: true,
        lazyLoad: true,
        autoplay: true,
        autoplayTimeout: 4000,
        margin: 20,
        nav: true,
        dots: false,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                nav: false,
                dots: true
            },
            576: {
                items: 2,
                nav: true,
                loop: false
            },
            768: {
                items: 2,
                nav: true,
                loop: false
            },
            992: {
                items: 3,
                nav: true,
                loop: false
            }
        }
    });
    $('.news-slider').owlCarousel({
        items: 4,
        loop: true,
        lazyLoad: true,
        autoplay: true,
        autoplayTimeout: 4000,
        margin: 20,
        nav: true,
        dots: false,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                nav: false,
                dots: true
            },
            576: {
                items: 2,
                nav: true
            },
            768: {
                items: 2,
                nav: true
            },
            992: {
                items: 3,
                nav: true
            },
            1199: {
                items: 4,
                nav: true
            }
        }
    });
    //Logo
    $('.logo-footer').owlCarousel({
        items: 3,
        loop: true,
        lazyLoad: true,
        autoplay: true,
        autoplayTimeout: 4000,
        margin: 10,
        nav: true,
        responsiveClass: true,
        responsive: {
            0: {
                items: 2,
                nav: false
            },
            576: {
                items: 3,
                nav: false
            },
            768: {
                items: 4,
                nav: true
            },
            992: {
                items: 5,
                nav: true,
                loop: true,

            }
        }
    });
    //Remove width
    $('#search-product-js').change(function () {
        if($(this).val() == ''){
            $(this).css('width','50px');
        }else{
            $(this).css('width','auto');
        }
    });
    //mobile
    $('[data-toggle="collapse"]').on('click', function () {
        $(this).toggleClass("collapse-in");
    });
    if (window.innerWidth < 992) {
        $('.nav-navbar-middle .dropdown > .nav-link').attr('data-toggle', 'dropdown');
        // $('.nav-navbar-middle .dropdown-submenu > .dropdown-item').addClass('dropdown-toggle');
        $('.dropdown-menu li .caret-down').on('click', function (e) {
            var $el = $(this);
            var $parent = $(this).offsetParent(".dropdown-menu");
            if (!$(this).next().hasClass('show')) {
                $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
            }
            var $subMenu = $(this).next(".dropdown-menu");
            $subMenu.toggleClass('show');

            $(this).parent("li").toggleClass('show');

            $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
                $('.dropdown-menu .show').removeClass("show");
            });

            if (!$parent.parent().hasClass('navbar-nav')) {
                $el.next().css({"top": $el[0].offsetTop, "left": $parent.outerWidth() - 4});
            }
            return false;
        });
    }
});
$(window).resize(function () {
    if (window.innerWidth < 992) {
        $('.nav-navbar-middle .dropdown > .nav-link').attr('data-toggle', 'dropdown');
        // $('.nav-navbar-middle .dropdown-submenu > .dropdown-item').addClass('dropdown-toggle');
        $('.dropdown-menu li .caret-down').on('click', function (e) {
            var $el = $(this);
            var $parent = $(this).offsetParent(".dropdown-menu");
            if (!$(this).next().hasClass('show')) {
                $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
            }
            var $subMenu = $(this).next(".dropdown-menu");
            $subMenu.toggleClass('show');

            $(this).parent("li").toggleClass('show');

            $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
                $('.dropdown-menu .show').removeClass("show");
            });

            if (!$parent.parent().hasClass('navbar-nav')) {
                $el.next().css({"top": $el[0].offsetTop, "left": $parent.outerWidth() - 4});
            }
            return false;
        });
    }
});
