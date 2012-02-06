/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Popup open button
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

var lastPopupButton;

function PopupButton()
{
  var obj = this;

  jQuery(this.pattern).each(
    function () {
      obj.eachCallback(this);
    }
  );
}

PopupButton.prototype.pattern = '.popup-button';

PopupButton.prototype.enableBackgroundSubmit = true;

PopupButton.prototype.options = {'width' : 'auto'};

PopupButton.prototype.afterSubmit = function (selector) {
}

PopupButton.prototype.callback = function (selector, link)
{
  var obj = this;

  if (this.enableBackgroundSubmit) {
    jQuery('form', selector).each(
      function() {
        jQuery(this).commonController(
          'enableBackgroundSubmit',
          function () {
            // Close dialog (but it is available in DOM)
            jQuery(selector).dialog('close');
            openWaitBar();

            return true;
          },
          function (event) {
            closeWaitBar();

            obj.afterSubmit(selector);

            // Remove dialog from DOM
            jQuery(selector).remove();
            return false;
          }
        );
      }
    );
  }
}

PopupButton.prototype.getURLParams = function (button)
{
  return core.getCommentedData(button, 'url_params');
}

PopupButton.prototype.eachClick = function (elem)
{
  lastPopupButton = jQuery(elem);

  return !lastPopupButton.hasClass('disabled') ? loadDialogByLink(
    elem,
    URLHandler.buildURL(this.getURLParams(elem)),
    this.options,
    this.callback,
    this
  ) : false;
}

PopupButton.prototype.eachCallback = function (elem)
{
    var button;
    var callback;
    var obj = this;

    button = elem;
    callback = obj.callback;

    jQuery(elem).click(
      function () {
        obj.eachClick(this);
      }
    );
}
