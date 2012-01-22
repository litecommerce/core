/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Switcher controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 */

CommonForm.elementControllers.push(
  {
    pattern: '.input-field-wrapper.switcher',
    handler: function () {
      var input = jQuery(':checkbox', this);
      var cnt = jQuery(this);
      var widget = jQuery('.widget', this);

      widget.click(
        function () {
          if (!input.attr('checked')) {
            input.attr('checked', 'checked');
            input.get(0).setAttribute('checked', 'checked');

          } else {
            input.removeAttr('checked');
          }

          input.change();
        }
      );

      input.change(
        function () {
          if (this.checked) {
            cnt.addClass('enabled').removeClass('disabled');
            widget.attr('title', widget.data('disable-label'));

          } else {
            cnt.removeClass('enabled').addClass('disabled');
            widget.attr('title', widget.data('enable-label'));
          }
        }
      );
    }
  }
);
