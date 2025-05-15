window.toggleMenu = function () {
    const menu = document.getElementById("navbarMenu");
    menu.classList.toggle("show");
};

window.onscroll = function () {
    const btn = document.getElementById("scrollTopBtn");
    btn.style.display = (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) ? "block" : "none";
};

window.scrollToTop = function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

onAuthStateChanged(auth, (user) => {
    document.getElementById("loginBtn").style.display = user ? "none" : "inline-block";
    document.getElementById("logoutBtn").style.display = user ? "inline-block" : "none";
});
// window.addEventListener('DOMContentLoaded', () => {
//     fetch('session_status.php')
//         .then(response => response.json())
//         .then(data => {
//             const loginBtn = document.getElementById('loginBtn');
//             const logoutBtn = document.getElementById('logoutBtn');

//             if (data.loggedIn) {
//                 if (loginBtn) loginBtn.style.display = 'none';
//                 if (logoutBtn) logoutBtn.style.display = 'inline-block';
//             } else {
//                 if (loginBtn) loginBtn.style.display = 'inline-block';
//                 if (logoutBtn) logoutBtn.style.display = 'none';
//             }
//         })
//         .catch(error => console.error('Error checking session status:', error));
// });

window.logout = () => {
    signOut(auth).then(() => {
        window.location.href = "index1.html";
    });
};

$(document).ready(function () {
    var scrollTop = $(window).scrollTop();
    var checkIfTop = (scrollTop == 0);
    var navbar = $('.navbar');
    var collapse = $('.collapse');

    if (screen.width <= 600) {
        navbar.css('background-color', 'transparent');
        collapse.css("background-color", "#242B35");
        collapse.css("border-color", "transparent");
    } else if (screen.width > 600 && scrollTop > 0) {
        navbar.css('background-color', '#242B35');
        $('.navbar li a, .navbar').css('font-size', '15px');
    } else {
        navbar.css('background-color', 'transparent');
        $('.navbar li a, .navbar').css('font-size', '12px');
    }



    $(document).on('click', '.navbar-collapse.in', function (e) {
        if ($(e.target).is('a') && $(e.target).attr('class') != 'dropdown-toggle') {
            $(this).collapse('hide');
        }
    });


    $(".slideanim").each(function () {
        var pos = $(this).offset().top;

        if (pos < scrollTop + 600) {
            $(this).removeClass("hideImage");
        } else {
            $(window).on('scroll', function () {
                $(".slideanim").each(function () {
                    var pos = $(this).offset().top;

                    if ((screen.height > 1000) && (pos < scrollTop + 800)) {
                        $(this).addClass("animated fadeInUp");
                        $(this).removeClass("hideImage");
                    } else if ((screen.height < 800) && (pos < scrollTop + 600)) {
                        $(this).addClass("animated fadeInUp");
                        $(this).removeClass("hideImage");
                    }
                });
            });
        }
    });

    $(window).on('scroll', function () {
        scrollTop = $(window).scrollTop();

        if (scrollTop == 0 && screen.width > 600) {
            checkIfTop = true;
            navbar.css('background-color', 'transparent');
            $('.navbar li a, .navbar').css('font-size', '12px');

        } else if (scrollTop != 0 && screen.width > 600) {
            if (checkIfTop) {
                checkIfTop = false;
                navbar.css('background-color', '#242B35');
                $('.navbar li a, .navbar').css('font-size', '15px');
            }
        }
    });

    $(".navbar a, .jumbotron a, footer a").on('click', function (event) {
        if (this.hash !== "") {
            event.preventDefault();
            var hash = this.hash;

            $('html, body').animate({
                scrollTop: $(hash).offset().top
            }, 900, function () {
                window.location.hash = hash;
            });
        }
    });
});

