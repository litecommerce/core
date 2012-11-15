/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Float field microcontroller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

CommonForm.elementControllers.push(
  {
    pattern: '.input-field-wrapper input.auto-complete',
    handler: function () {

      if ('undefined' == typeof(this.autocompleteSource)) {
        this.autocompleteSource = function(request, response)
        {
          core.get(
            unescape(jQuery(this).data('source-url')).replace('%term%', request.term),
            null,
            {},
            {
              dataType: 'json',
              success: function (data) {
                response(data);
              }
            }
          );
        }
      }

      if ('undefined' == typeof(this.autocompleteAssembleOptions)) {
        this.autocompleteAssembleOptions = function()
        {
          var input = this;

          return {
            source: function(request, response) {
              input.autocompleteSource(request, response);
            },
            minLength: jQuery(this).data('min-length') || 2,
          };
        }
      }

      jQuery(this).autocomplete(this.autocompleteAssembleOptions());
    }
  }
);

