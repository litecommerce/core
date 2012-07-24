/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Sticky panel controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

// TODO: move this bind/trigger events to the main widget definition

jQuery().ready(
  function () {
    jQuery('.sticky-panel').each(
      function () {

        var box     = jQuery(this);
        var cancel  = jQuery('.cancel', box);
        var buttons = jQuery('button', box);
        var form    = box.closest('form');

        form.bind(
          'sticky_undo_buttons',
          function (e){
            buttons.each(
              function() {
                this.disable();
              }
            );

            cancel.addClass('disabled');
          }
        );

        form.bind(
          'sticky_changed_buttons',
          function (e){
            buttons.each(
              function() {
                this.enable();
              }
            );

            cancel.removeClass('disabled');
          }
        );

        cancel.bind(
          'click',
          function (e) {
            box.trigger('sticky_undo_buttons');
          }
        );

      }
    );
  }
);


