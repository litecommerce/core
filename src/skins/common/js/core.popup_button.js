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

PopupButton.prototype.options = {'width' : 'auto'};

PopupButton.prototype.callback = function (selector)
{
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
        var urlParams;
        urlParams = core.getCommentedData(button, 'url_params');
        lastPopupButton = jQuery(this);

        return loadDialogByLink(
          button,
          URLHandler.buildURL(urlParams),
          obj.options,
          callback
        );
      }
    );
}
