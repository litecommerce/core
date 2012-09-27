/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * File selector button and popup controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

function PopupButtonSaleSelectedButton()
{
  PopupButtonSaleSelectedButton.superclass.constructor.apply(this, arguments);
}

extend(PopupButtonSaleSelectedButton, PopupButton);

PopupButtonSaleSelectedButton.prototype.pattern = '.sale-selected-button';

decorate(
  'PopupButtonSaleSelectedButton',
  'callback',
  function (selector, link)
  {
    // previous method call
    arguments.callee.previousMethod.apply(this, arguments);

    // Autoloading of browse server link (popup widget).
    // TODO. make it dynamically and move it to ONE widget initialization (Main widget)
    core.autoload(SalePriceValue);
  }
);

decorate(
  'PopupButtonSaleSelectedButton',
  'afterSubmit',
  function (selector)
  {
    // previous method call
    arguments.callee.previousMethod.apply(this, arguments);

    var value = jQuery('input[name="postedData[salePriceValue]"]').val();

    var participateSale = 'sale_percent' == jQuery('input[name="postedData[discountType]"]:checked').val()
      ? ((value > 0) && (value <= 100))
      : (value >= 0);

    jQuery('input[name*="select"]', jQuery(selector)).each(function (index, elem) {

      if (participateSale) {
        jQuery('.entity-' + jQuery(elem).val() + ' .product-name-sale-label')
          .removeClass('product-name-sale-label-disabled');
      } else {
        jQuery('.entity-' + jQuery(elem).val() + ' .product-name-sale-label')
          .addClass('product-name-sale-label-disabled');
      }
    });
  }
);

decorate(
  'PopupButtonSaleSelectedButton',
  'getURLParams',
  function (button)
  {
    var urlParams = arguments.callee.previousMethod.apply(this, arguments);

    jQuery('[name*="select"]:checked').each(function (index, elem) {urlParams[elem.name] = 1});

    return urlParams;
  }
);

decorate(
  'PopupButtonSaleSelectedButton',
  'eachClick',
  function (elem)
  {
    if (elem.linkedDialog) {
      jQuery(elem.linkedDialog).dialog('close').remove();
      elem.linkedDialog = undefined;
    }

    return arguments.callee.previousMethod.apply(this, arguments);
  }
);

core.autoload(PopupButtonSaleSelectedButton);
