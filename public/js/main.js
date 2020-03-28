$(document).ready(function() {
    backtopButton();
});

var backtopButton = function() {
    var btn = $('#back-top');
    $(window).scroll(function() {
      if ($(window).scrollTop() > 300) {
        btn.show();
      } else {
        btn.hide();
      }
    });
};