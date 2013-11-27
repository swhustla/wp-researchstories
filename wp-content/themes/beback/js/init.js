(function($) {
  BeBack = {
    init: function() {
      if ( /%/.test($("#spaceused1").text() ) ) { 
        $("#spaceused1").progressBar({
          boxImage: BeBack_Settings.progressBoxImage,
          barImage: BeBack_Settings.progressBarImage,
          width: 646,
          height: 52
        });
      }

      $('.countdown').countdown({
        until: new Date( BeBack_Settings.launchDate_year, BeBack_Settings.launchDate_month - 1, BeBack_Settings.launchDate_day ),
        layout: '{dn} {dl}, {hn} {hl}, {mn} {ml}, {sn} {sl}'
      });
    }
  }
})(jQuery);

jQuery(document).ready(function() { 
    BeBack.init();
});
