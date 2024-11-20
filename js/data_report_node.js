(function ($, Drupal, cookies) {

  "use strict";


  Drupal.behaviors.reportNode = {
    attach: function (context, settings) {
      // Get text of .flag a
      let favButtonText = $('.flag a').text();
      let name = 'lastClickedReportNode' + "=";
      let decodedCookie = decodeURIComponent(document.cookie);
      let ca = decodedCookie.split(';');
      let favorited = 0;
      // Set favorited to 1 if the cookie contains the last clicked reportNode
      for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
          c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
          favorited = 1;
        }
      }
      $('.flag-oda-reports a').click(function() {
        var now = new Date();
        var time = now.getTime();
        var expireTime = time + 10*1000;
        now.setTime(expireTime);
        document.cookie = name+$(this).attr('href')+';expires='+now.toUTCString()+';path=/';
      });
      if (favorited == 1) {
        $('.flag a').focus();
        var div = document.getElementById('userupdateinfo');
        var userAlert = favButtonText == 'Favorite' ? 'This report has been removed from your favorites.': 'This report has been added to your favorites.';
        div.innerHTML = userAlert;
      }
    }
  };

})(jQuery, Drupal, window.Cookies);
