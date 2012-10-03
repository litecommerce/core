/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Attributes
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

var ppr = popup.postprocessRequest;
popup.postprocessRequest = function(XMLHttpRequest, textStatus, data, isValid) {
  ppr.call(this, XMLHttpRequest, textStatus, data, isValid);
  TableItemsListQueue();

  jQuery('.select-attributetypes select').change(
    function () {
      if (jQuery(this).data('value') == jQuery(this).val()) {
        jQuery('.select-attributetypes .form-field-comment').hide();
      } else {
        jQuery('.select-attributetypes .form-field-comment').show();
      }
    }
  );
}

var pprc = popup.postprocessRequestCallback;
popup.postprocessRequestCallback()  = function()
{
  pprc.postprocessRequestCallback();
  popup.close();
}

function popup_attribute_groups(product_class_id) {
  return !popup.load(
    URLHandler.buildURL({
      target:             'attribute_groups',
      product_class_id:   product_class_id,
      widget:             'XLite\\View\\AttributeGroups'
    })
  );
}

function popup_attribute(product_class_id, id) {
  return !popup.load(
    URLHandler.buildURL({
      target:             'attribute',
      product_class_id:   product_class_id,
      id:                 id,
      widget:             'XLite\\View\\Attribute'
    }),
    null,
    function () {
      self.location.reload();
    }
  );
}
