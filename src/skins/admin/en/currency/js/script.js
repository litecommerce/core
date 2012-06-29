/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Currency page routines
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

function CurrencyManageForm()
{
  this.initialize();
}

CurrencyManageForm.prototype.patternCurrencyViewInfo = '.currency-view-info *';

CurrencyManageForm.prototype.initialize = function ()
{
  var obj = this;

  jQuery('#currency-id').change(
    function () {
      jQuery(this).closest('form').trigger('sticky_undo_buttons');
      document.location = URLHandler.buildURL({'target': 'currency', 'currency_id': jQuery(this).val()});
    }
  );

  jQuery('#format').change(function() {
    jQuery(obj.patternCurrencyViewInfo).trigger(
      'formatCurrencyChange',
      [
        jQuery(this).val(),
        jQuery(this).data('e'),
        jQuery(this).data('thousandPart'),
        jQuery(this).data('hundredsPart'),
        jQuery(this).data('delimiter')
      ]
    );
  });

  jQuery('#prefix').keyup(function(event) {
    jQuery(obj.patternCurrencyViewInfo).trigger('prefixCurrencyChange', [jQuery(this).val()]);
  });

  jQuery('#suffix').keyup(function(event) {
    jQuery(obj.patternCurrencyViewInfo).trigger('suffixCurrencyChange', [jQuery(this).val()]);
  });

  jQuery('#trailing-zeroes').bind(
    'trailingZeroesClick',
    function (event) {
      jQuery(obj.patternCurrencyViewInfo).trigger('trailingZeroesClick',[jQuery(this).attr('checked')]);
    }
  ).click(function (event) {
      jQuery(this).trigger('trailingZeroesClick');
  });

  jQuery(document).ready(function () {
    jQuery('#format').trigger('change');

    jQuery('#prefix, #suffix').trigger('keyup');

    jQuery('#trailing-zeroes').trigger('trailingZeroesClick');

    jQuery('#format').bind(
      'change',
      function (e) {
        jQuery(this).closest('form').trigger('sticky_changed_buttons');
      }
    );

    jQuery('#prefix, #suffix').bind(
      'keyup',
      function (e) {
        jQuery(this).closest('form').trigger('sticky_changed_buttons');
      }
    );

    jQuery('#trailing-zeroes').bind(
      'click',
      function (e) {
        jQuery(this).closest('form').trigger('sticky_changed_buttons');
      }
    );

  });
}

core.autoload(CurrencyManageForm);
