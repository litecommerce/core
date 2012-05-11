/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Permissions selector controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.22
 */

jQuery(document).ready(
  function () {
    jQuery('li.permissions select').multiselect({
      open: function (event, ui) {
        if ('undefined' == typeof(event.target.minicontrollerAssigned)) {
          var rootId;
          jQuery(event.target.options).each(
            function () {
              if (jQuery(this).data('isRoot')) {
                rootId = this.value;
              }
            }
          );

          if (rootId) {
            var inp = jQuery('.ui-multiselect-menu').find(':checkbox[value="' + rootId + '"]');
            inp.change(
              function () {
                var box = jQuery(this).parents('.ui-multiselect-menu').eq(0).find(':checkbox').not('[value="' + rootId + '"]');
                if (this.checked) {
                  box.each(
                    function () {
                      this.prevCheckesState = this.checked;
                      this.checked = true;
                    }
                  ).attr('disabled', 'disabled');

                } else {
                  box.each(
                    function () {
                      if ('undefined' != typeof(this.prevCheckesState)) {
                        this.checked = this.prevCheckesState;
                      }
                    }
                  ).removeAttr('disabled');
                }
              }
            );
            inp.change();
          }

          event.target.minicontrollerAssigned = true;
        }
      }
    });
  }
);
