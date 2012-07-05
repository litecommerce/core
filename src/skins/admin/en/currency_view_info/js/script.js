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

function CurrencyViewInfo()
{
  this.initialize();
}

CurrencyViewInfo.prototype.initialize = function ()
{
  jQuery('.currency-view-info .currency.currency-zero .format').bind(
    'formatCurrencyChange',
    function(e, value, exp, thousand, hundreds, delimiter) {
      var format = value.split(delimiter);

      jQuery(this).html(thousand + format[0] + hundreds);
    }
  );

  jQuery('.currency-view-info .currency.currency-zero .decimal').bind(
    'formatCurrencyChange',
    function(e, value, exp, thousand, hundreds, delimiter) {
      if (0 == exp) {
        jQuery(this).html('');
      } else {
        var format = value.split(delimiter);

        jQuery(this).html(format[1] + (new Array(exp + 1).join('0')));
      }
    }
  ).bind(
    'trailingZeroesClick',
    function (e, value) {
      if (value) {
        jQuery(this).hide();
      } else {
        jQuery(this).show();
      }
    }
  );

  jQuery('.currency-view-info .currency .prefix').bind('prefixCurrencyChange', function(e, value) {jQuery(this).html(htmlspecialchars(value, null, null, false));});

  jQuery('.currency-view-info .currency .suffix').bind('suffixCurrencyChange', function(e, value) {jQuery(this).html(htmlspecialchars(value, null, null, false));});
}

core.autoload(CurrencyViewInfo);
