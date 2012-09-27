/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Taxes controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

jQuery().ready(
  function() {

    jQuery('.edit-sales-tax table.form button.switch-state')
      .removeAttr('onclick')
      .click(
      function() {
        var o = this;
        o.disabled = true;
        var page = jQuery(this).parents('form').get(0).elements.namedItem('page').value;
        core.post(
          URLHandler.buildURL({target: 'taxes', action: 'salesTaxSwitch', page: page}),
          function(XMLHttpRequest, textStatus, data, valid) {
            o.disabled = false;
            if (valid) {
              var td = jQuery('.edit-sales-tax table.form td.button');
              if (td.hasClass('enabled')) {
                td.removeClass('enabled');
                td.addClass('disabled');
              
              } else {
                td.removeClass('disabled');
                td.addClass('enabled');
              }
            }
          }
        );

        return false;
      }
    );

    var checkTaxRatesState = function()
    {
      if (3 < jQuery('.edit-sales-tax table.data tr').length) {
        jQuery('.edit-sales-tax table.data').removeClass('empty-data');

      } else {
        jQuery('.edit-sales-tax table.data').addClass('empty-data');
      }
    }

    var lastNewRowId = -1;

    jQuery('.edit-sales-tax button.new-rate').click(
      function () {
        var base = jQuery('.edit-sales-tax tr.new-template');
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

        checkTaxRatesState();

        return false;
      }
    );

    jQuery('.edit-sales-tax td.rate button.rate-remove').click(
      function () {
        var match = jQuery(this).parents('td').eq(0).find('input').attr('name').match(/rates.(-?[0-9]+)./);
        var id = parseInt(match[1]);

        if (!isNaN(id)) {
          if (id > 0) {
            var row = jQuery(this).parents('tr').eq(0);
            jQuery('input,select,button', row).attr('disabled', 'disabled');
            var page = jQuery(this).parents('form').get(0).elements.namedItem('page').value;
            core.post(
              URLHandler.buildURL({target: 'taxes', action: 'salesTaxRemoveRate', page: page, id: id}),
              function(XMLHttpRequest, textStatus, data, valid) {
                jQuery('input,select,button', row).removeAttr('disabled');
                if (valid) {
                  row.remove();
                  checkTaxRatesState();
                }
              }
            );

          } else {
            jQuery(this).parents('tr').eq(0).remove();
            checkTaxRatesState();
          }
        }

        return false;
      }
    );
  }
);

