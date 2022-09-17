(function($) {
  "use strict"; // Start of use strict

  // Toggle the side navigation
  $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    if ($(".sidebar").hasClass("toggled")) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Close any open menu accordions when window is resized below 768px
  $(window).resize(function() {
    if ($(window).width() < 768) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
    if ($(window).width() > 768) {
      var e0 = e.originalEvent,
        delta = e0.wheelDelta || -e0.detail;
      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  });

  // Scroll to top button appear
  $(document).on('scroll', function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });

  // Smooth scrolling using jQuery easing
  $(document).on('click', 'a.scroll-to-top', function(e) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    e.preventDefault();
  });

  var tableList = ["mySidenav", "fileSidenav", "formSidenav"];
  $(document).mouseup(function(e) {
    for (var i = 0; i < tableList.length; i++) {
      var container = $("#" + tableList[i]);
      if (!container.is(e.target) && container.has(e.target).length === 0 && container.hasClass('active')) {
        // container.removeClass("active");
      }
    }
  });

  $(".openMySidenav, .closeMySidenav").on('click', function(e) {
    $("#mySidenav").toggleClass("active");
  });
  $(".openMySidenav1, .closeMySidenav1").on('click', function(e) {
    $("#mySidenav1").toggleClass("active");
  });

 $(".openMySidenav01, .closeMySidenav01").on('click', function(e) {
    $("#mySidenav01").toggleClass("active");
  });


  $(".appointments-list .btn-info, #closeFileSidenav").on('click', function(e) {
    $("#fileSidenav").toggleClass("active");
  });

  $(".appointments-list .btn-warning, #closeFormSidenav").on('click', function(e) {
    $("#formSidenav").toggleClass("active");
  });

  setInterval(function time(){
    if (document.getElementById("clock")) document.getElementById("clock").innerHTML = moment().format('LLLL');;
  }, 1000);

})(jQuery); // End of use strict
