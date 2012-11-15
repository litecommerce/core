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
        jQuery('li.custom-field').show();
      } else {
        jQuery('.select-attributetypes .form-field-comment').show();
        jQuery('li.custom-field').hide();
      }
    }
  );

  jQuery('.ajax-container-loadable form.attribute', this.base).commonController('submitOnlyChanged', false); 
}

jQuery().ready(
  function () {
    jQuery('button.manage-groups').click(
      function () {
        return !popup.load(
          URLHandler.buildURL({
            target:             'attribute_groups',
            product_class_id:   jQuery(this).parent().data('class-id'),
            widget:             'XLite\\View\\AttributeGroups'
          }),
          null,
          function () {
            self.location.reload();
          }
        );
      }
    );
    jQuery('button.new-attribute, a.edit-attribute').click(
      function () {
        return !popup.load(
          URLHandler.buildURL({
            target:             'attribute',
            product_class_id:   jQuery(this).parent().data('class-id'),
            id:                 jQuery(this).parent().data('id'),
            widget:             'XLite\\View\\Attribute'
          }),
          null,
          function () {
            self.location.reload();
          }
        );
      }
    );
  }
);
