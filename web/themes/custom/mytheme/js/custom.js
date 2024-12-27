(function ($, Drupal) {
    Drupal.behaviors.mythemeBehavior = {
      attach: function (context, settings) {
        console.log('Custom JavaScript loaded!');
      }
    };
  })(jQuery, Drupal);
  