/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * File selector button and popup controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.6
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

    var participateSale = 0 != jQuery('input[name="postedData[salePriceValue]"]').val()
      || 'sale_percent' != jQuery('input[name="postedData[discountType]"]:checked').val();

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

decorate(
  'PopupButtonSaleSelectedButton',
  'eachCallback',
  function (elem)
  {
    var button;
    var callback;
    var obj = this;

    button = elem;
    callback = obj.callback;

    jQuery(elem).click(
      function () {

        var urlParams;

        urlParams = core.getCommentedData(button, 'url_params');

        jQuery('[name*="select"]:checked').each(function (index, elem) {urlParams[elem.name] = 1});

        lastPopupButton = jQuery(this);

        if (!jQuery(this).hasClass('disabled')) {
          return loadDialogByLink(
            button,
            URLHandler.buildURL(urlParams),
            obj.options,
            callback
          );
        }
      }
    );
  }
  );


core.autoload(PopupButtonSaleSelectedButton);
