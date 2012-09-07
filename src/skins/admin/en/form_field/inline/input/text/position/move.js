/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Move controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

CommonForm.elementControllers.push(
  {
    pattern: 'tbody.lines',
    condition: '.inline-field.inline-move',
    handler: function (form) {

      jQuery(this).find('.inline-move .move').disableSelection();

      var tds = jQuery(this).find('td');

      tds.each(
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
            if (ui.item.hasClass('remove-mark')) {
              ui.item.parent().sortable('cancel');

            } else {
              tds.each(
                function () {
                  var td = jQuery(this);
                  td.width(td.data('saved-width'));
                }
              );
            }
          },
          update: function(event, ui)
          {
            ui.item.css('width', 'auto');
            tds.removeAttr('style');

            // Reassign position values
            var min = 0;
            form.find('.inline-field.inline-move input').each(
              function () {
                min = 0 == min ? this.value : Math.min(this.value, min);
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

