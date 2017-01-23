(function ($) {

Drupal.behaviors.customSloganFieldsetSummaries = {
  attach: function (context) {
    $('fieldset#edit-custom-slogan', context).drupalSetSummary(function (context) {
      var pt = $('input', context).val();

      return pt ?
        Drupal.t('Custom Slogan: @pt', { '@pt': pt }) :
        Drupal.t('No Custom Slogan');
    });
  }
};


Drupal.behaviors.customSloganCounter = {
  attach : function(context) {
    $('fieldset#edit-custom-slogan', context).each(function() {
      function getLength(element) { return $(element).val().length; }

      var wrapper = this;

      var inputBox = $('input[name=custom_slogan]', wrapper);

      var valueBox = $('div.description', wrapper)
                      .append('<br/><span class="counter">Characters Entered: <span class="value">0</span></span>')
                      .find('.value')
                      .text(getLength(inputBox));

      $('input[name=custom_slogan]', wrapper).keyup(function(e) { $(valueBox).text(getLength(inputBox)); });
    });
  }

}

})(jQuery);


