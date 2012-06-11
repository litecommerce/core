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
  this.callback();
}

CurrencyManageForm.prototype.patternCurrencyViewInfo = '.currency-view-info *';

CurrencyManageForm.prototype.callback = function ()
{
  var obj = this;

  jQuery('#currency-id').change(function () {
    document.location = URLHandler.buildURL({'target': 'currency', 'currency_id': jQuery(this).val()});
  });

  jQuery('#format').change(function() {
    jQuery(obj.patternCurrencyViewInfo).trigger('formatCurrencyChange', [jQuery(this).val()]);
  }).trigger('change');

  jQuery('#prefix').change(function() {
    jQuery(obj.patternCurrencyViewInfo).trigger('prefixCurrencyChange', [jQuery(this).val()]);
  }).trigger('change');

  jQuery('#suffix').change(function() {
    jQuery(obj.patternCurrencyViewInfo).trigger('suffixCurrencyChange', [jQuery(this).val()]);
  }).trigger('change');

}

