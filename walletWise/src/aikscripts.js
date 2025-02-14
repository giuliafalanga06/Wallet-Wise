$("body").css("overflow-y", "hidden");

setInterval(() => {
    $("#bubble").children().first().css({"background": "linear-gradient(to right, hsl(136, 65%, 51%), hsl(192, 70%, 51%))", "transform": "translateY(-5px)"});
    window.setTimeout( () => {
        $("#bubble").children().first().css({"background": "hsl(233, 8%, 62%)", "transform": "translateY(0)"});
        $("#bubble").children().first().next().css({"background": "linear-gradient(to right, hsl(136, 65%, 51%), hsl(192, 70%, 51%))", "transform": "translateY(-5px)"});
    }, 500)
    window.setTimeout( () => {
        $("#bubble").children().first().next().css({"background": "hsl(233, 8%, 62%)", "transform": "translateY(0)"});
        $("#bubble").children().last().css({"background": "linear-gradient(to right, hsl(136, 65%, 51%), hsl(192, 70%, 51%))", "transform": "translateY(-5px)"});
    }, 1000)
    window.setTimeout( () => {
        $("#bubble").children().last().css({"background": "hsl(233, 8%, 62%)", "transform": "translateY(0)"});
    }, 1500)
}, 2000)


function preloader () {
    $("#preloader").css("display","none");
    $("body").css("overflow-y", "scroll");
}

$(".nav_list > a").mouseover( (e) => {$(e.target).next().addClass("border_active")}); 
    
$(".nav_list > a").mouseout((e) => {$(e.target).next().removeClass("border_active")});

$("#hamburger_icon").click( () => {
    if ($("#hamburger_icon").attr("src") === "./images/icon-hamburger.svg") {
        $("#hamburger_icon").attr("src", "./images/icon-close.svg");
        $("#mockups").css("display", "none"); 
        $("#mockups").parent().prev().css({"padding-top": "82%", "background": "linear-gradient( hsla(233, 26%, 24%, 0.75),hsl(0, 0%, 100%))"});
        $("#hamburger_list").css("display", "flex"); 
    } else {
        $("#hamburger_icon").attr("src", "./images/icon-hamburger.svg");
        $("#mockups").css("display", "block"); 
        $("#mockups").parent().prev().css({"padding-top": "0%", "background": "transparent"});
        $("#hamburger_list").css("display", "none");
    }
    
});

$(".ham_border").prev().mouseover((e) => {$(e.target).next().addClass("ham_border_active")});
$(".ham_border").prev().mouseout((e) => {$(e.target).next().removeClass("ham_border_active")})
// Toggle sidebar menu
$("nav.sidebar > figure:first-child").click(() => {
    $("nav.sidebar").toggleClass("open");
    $("body").toggleClass("sidebar-open");
});


// Ensure the sidebar starts closed and toggle works
$(document).ready(() => {
    $("nav.sidebar").removeClass("open"); // Ensure the sidebar starts closed

    $("#menu-toggle").click(() => {
        $("nav.sidebar").toggleClass("open");
        $("body").toggleClass("sidebar-open");
    });
});

// Toggle sidebar menu
$("#hamburger_icon, .logo img").click(() => {
    $("nav.sidebar").toggleClass("open");
    $("body").toggleClass("sidebar-open");
});
