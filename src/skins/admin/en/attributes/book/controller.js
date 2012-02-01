/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * JS support for the attributes book
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.14
 */

jQuery().ready(
  function() {
    jQuery('#new_attribute_button').click(
      function () {
        jQuery('div.group.hidden').hide();
      }
    );

    jQuery('#new_group_button').click(
      function () {
        jQuery('div.attribute.hidden').hide();
      }
    );

    jQuery('.attribute-change-label').click(
      function () {
        jQuery(this).nextAll('table.attribute-properties').toggle();
      }
    );

    jQuery('.group .delete.group').click(
      function () {
        jQuery(this).children('input[name*="toDelete"]').find('div.group-attributes').toggleClass('row-to-delete');
      }
    );

    jQuery('select[name="postedData[_][attributes][_][class]"]').change(
      function () {
        var parentRow = jQuery(this).parents('tr');

        parentRow.nextAll('tr.additional-properties').hide();
        parentRow.nextAll('#' + jQuery("option:selected",this).val().toLowerCase() + '_additional').show();
      }
    ).change();

    jQuery('.attribute').each(
      function (idx, elem) {
        var title = jQuery('.title', elem)
        title.show();

        var input = jQuery('input', elem)
        input.hide();

        title.click(function () {
          title.hide();
          input.show().focus();
        }).blur(function () {
          title.show();
          input.hide();
        });

        jQuery(elem).closest('.row').hover(
          function () {
            // Mouse in the element
            jQuery(elem).addClass('hover');
          },
          function () {
            // Mouse out of the element
            jQuery(elem).removeClass('hover');
          }
        );
      }
    );

    jQuery('.group').each(
      function (idx, elem) {
        jQuery(elem).closest('.row').addClass('group-row');

        jQuery('.group-title', elem).dblclick(
          function () {
            jQuery('.group-attributes, .group-title', elem).toggleClass('opened');
            jQuery(elem).closest('.row').toggleClass('opened-group');
          }
        );
      }
    );

  }
);
