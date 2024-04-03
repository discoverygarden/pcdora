(function ($) {
  Drupal.behaviors.disableRightClick = {
    attach: function (context, settings) {
      $('#islandora-video-preview').on('contextmenu', function (e) {
        // Prevent the default context menu from appearing.
        e.preventDefault();
      });
    }
  };
})(jQuery);
