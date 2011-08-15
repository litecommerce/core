/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Taxes controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

jQuery().ready(
  function() {

    var lastNewRowId = -1;

    jQuery('.edit-tax button.new-rate').click(
      function () {
        var base = jQuery('.edit-tax tr.new-template');
        var row = base.clone(true);
        row.insertBefore(base);
        row.removeClass('new-template');
        row.find('input,select').each(
          function() {
            this.name = this.name.replace(/%/, lastNewRowId);
          }
        );
        lastNewRowId--;
        row.show();

        return false;
      }
    );

    jQuery('.edit-tax td.rate button.rate-remove').click(
      function () {
        var match = jQuery(this).parents('td').eq(0).find('input').attr('name').match(/rates.(-?[0-9]+)./);
        var id = parseInt(match[1]);

        if (!isNaN(id)) {
          if (id > 0) {
            var row = jQuery(this).parents('tr').eq(0);
            jQuery('input,select,button', row).attr('disabled', 'disabled');
            var page = jQuery(this).parents('form').get(0).elements.namedItem('page').value;
            core.post(
              URLHandler.buildURL({target: 'taxes', action: 'removeRate', page: page, id: id}),
              function() {
                row.remove();
              }
            );

          } else {
            jQuery(this).parents('tr').eq(0).remove();
          }
        }

        return false;
      }
    );
  }
);

