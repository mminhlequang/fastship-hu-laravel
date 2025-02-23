$(document).ready(function() {
  $(".header-nav .nav-item").hover(function() {
    $(".nav-item")
      .not($(this))
      .find(".dropdown-icon")
      .removeClass("active");
    $(".nav-item")
      .not($(this))
      .removeClass("active");
    $(this)
      .find(".dropdown-icon")
      .toggleClass("active");
    $(this).toggleClass("active");
  });

  $(".sidebar .dropdown-btn").click(function() {
    $(".dropdown-btn")
      .not($(this))
      .parent(".nav-item")
      .children(".dropdown-container")
      .slideUp("200");
    $(this)
      .parent(".nav-item")
      .children(".dropdown-container")
      .slideToggle("200");
    $(this)
      .find(".dropdown-icon")
      .toggleClass("active");
  });

  $(".header-menu .sidebarBtn").click(function() {
    $(".sidebar").toggleClass("active");
    $(this).removeClass("d-block");
    $(this).addClass("d-none");
    $(".sidebar").css("box-shadow", "0 0 10px rgba(255, 255, 255, 0.9)");
    $("#overlay").show();
  });

  $(".sidebar .sidebarBtn").click(function() {
    $(".sidebar").toggleClass("active");
    $(".header-menu .sidebarBtn").removeClass("d-none");
    $(".header-menu .sidebarBtn").addClass("d-block");
    $("#overlay").hide();
    $(".sidebar").css("box-shadow", "none");
  });

  $("#overlay").click(function(e) {
    if ($(".sidebar").hasClass("active")) {
      $(".sidebar").removeClass("active");
      $(".sidebarBtn").addClass("d-block");
    }
    $(".dropdown-container").hide();
    $("#overlay").hide();
    $(".sidebar").css("box-shadow", "none");
  });

  function showButton() {
    var button = $(".backtop"), //button that scrolls user to top
      view = $(window),
      timeoutKey = -1;

    $(document).on("scroll", function() {
      if (timeoutKey) {
        window.clearTimeout(timeoutKey);
      }
      timeoutKey = window.setTimeout(function() {
        if (view.scrollTop() < 100) {
          button.fadeOut();
        } else {
          button.fadeIn();
        }
      }, 100);
    });
  }

  $(".backtop").on("click", function() {
    $("html, body")
      .stop()
      .animate(
        {
          scrollTop: 0,
        },
        500,
        "linear"
      );
    return false;
  });

  //call function on document ready
  $(function() {
    showButton();
  });
});
