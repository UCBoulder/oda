(function($, Drupal, cookies) {

  "use strict";

  function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }

  if ( getCookie("report-layout") == "one-column" ) {
    reportOneColumn();
  } else {
    reportTwoColumn();
  }

  $(".layout dd").click(function () {
    setLayout(this);
  });

  function setLayout(layout) {
    if($(layout).attr('class') == "layout--one" ) {
      document.cookie = "report-layout=one-column";
      reportOneColumn();
    }
    if($(layout).attr('class') == "layout--two" ) {
      document.cookie = "report-layout=two-column";
      reportTwoColumn();
    }
  }


  function reportOneColumn() {
    $(".views-element-container .row-wrapper").removeClass("two-column");
    $('.views-element-container .row-wrapper').addClass('one-column');
    $(".layout--two").removeClass("active");
    $(".layout--one").addClass("active");
  }

  function reportTwoColumn() {
    $(".views-element-container .row-wrapper").removeClass("one-column");
    $('.views-element-container .row-wrapper').addClass('two-column');
    $(".layout--one").removeClass("active");
    $(".layout--two").addClass("active");
  }

  if ( $(".close-filter").length == 0 ) {
    $(".views-exposed-form").prepend('<button class="close-filter" aria-label="close">X</button>');
  }

  function toggleSidebar() {
    $("aside").toggleClass("open");
    // wait 200ms before focusing on the input
    setTimeout(function(){
      if ( $("aside").is(":visible") ) {
        $("input[name='search_api_fulltext']").focus();
      }
    }, 200);
  }

  Drupal.behaviors.reports = {
    attach: function (context, settings) {
      if ( getCookie("report-layout") == "one-column" ) {
        reportOneColumn();
      } else {
        reportTwoColumn();
      }

      var layoutClick = $._data($("dd")[0], "events")

      if (layoutClick == undefined) {
        $(".layout dd").click(function () {
          setLayout(this);
        });
      }

      var filterByClick = $._data($("button.filter-by")[0], "events")

      if (filterByClick == undefined) {
        $("button.filter-by, button.close-filter, .off-canvas-wrapper", context).click(function () {
          toggleSidebar();
        });
      }

    }
  };


})(jQuery, Drupal, window.Cookies);
