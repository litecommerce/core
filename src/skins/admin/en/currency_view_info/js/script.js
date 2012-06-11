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
  this.callback();
}

CurrencyViewInfo.prototype.callback = function ()
{
  jQuery('.currency-view-info .currency .format').bind('formatCurrencyChange', function(e, value) {jQuery(this).html(value);});

  jQuery('.currency-view-info .currency .prefix').bind('prefixCurrencyChange', function(e, value) {jQuery(this).html(value);});

  jQuery('.currency-view-info .currency .suffix').bind('suffixCurrencyChange', function(e, value) {jQuery(this).html(value);});
}

// View must be loaded before the currency manage form controller
core.autoload(CurrencyViewInfo);

core.autoload(CurrencyManageForm);