/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Move controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 */

CommonForm.elementControllers.push(
  {
    pattern: 'tbody.lines',
    condition: '.inline-field.inline-move',
    handler: function (form) {

      jQuery(this).find('.inline-move .move').disableSelection();

      jQuery(this).find('td').each(
        function () {
          var td = jQuery(this);
          td.data('saved-width', td.width());
        }
      );

      jQuery(this).sortable(
        {
          axis: 'y',
          handle: '.inline-move .move',
          start: function (event, ui)
          {
            ui.item.find('td').each(
              function () {
                var td = jQuery(this);
                td.attr('style', 'width: ' +  td.data('saved-width') + 'px');
              }
            );
          },
          update: function(event, ui)
          {
            ui.item.css('width', 'auto');
            ui.item.find('td').removeAttr('style');

            // Reassign position values
            var min = 0;
            form.find('.inline-field.inline-move input').each(
              function () {
                if (0 == min) {
                  min = this.value;

                } else {
                  min = Math.min(this.value, min);
                }
              }
            );

            form.find('.inline-field.inline-move input').each(
              function () {
                jQuery(this).attr('value', min);
                jQuery(this).parents('.inline-move').eq(0).find('.move').attr('title', min);
                min++;
              }
            );

            // Change
            ui.item.parents('tbody.lines').trigger('positionChange');
            ui.item.parents('form').change();
          }
        }
      );
    }
  }
);

