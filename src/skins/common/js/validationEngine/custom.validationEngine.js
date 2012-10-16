(function($){

  $.validationEngineLanguage.allRules.onlySmallLetterNumberUnder = {
    "regex": /^[a-z][0-9a-z_]+$/,
    "alertText": "* Only small letter, digits and undescore sign are allowed"
  };

})(jQuery);