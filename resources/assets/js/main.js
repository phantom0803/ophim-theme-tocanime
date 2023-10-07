function lazyload() {
    $(".lazy").lazyload().removeClass("lazy");
}
$(document).ajaxStop(function () {
    setTimeout(lazyload, 500);
});
$(window).load(function () {
    lazyload();
});
