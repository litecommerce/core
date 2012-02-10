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
        jQuery('div.attribute').toggleClass('hidden');
      }
    );

    jQuery('#new_group_button').click(
      function () {
        jQuery('div.group').toggleClass('hidden');
      }
    );

    jQuery('.attribute-change-label-frame').click(
      function () {
        jQuery(this).closest('.row').toggleClass('open-attribute');
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

    jQuery('.attributes-entry-element').each(
      function (idx, elem) {

        var input = jQuery('input', elem);

        if (input.val() != '') {
          var title = jQuery('.title', elem);

          title.show();

          input.hide();

          title.click(function () {
            title.hide();
            input.show().focus();
          });

          input.blur(function () {
            title.show();
            input.hide();
          });
        }
      }
    );

    jQuery('.attribute').each(
      function (idx, elem) {

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

        jQuery('.group-title .open-group', elem).click(
          function (event) {
            jQuery(elem).closest('.row').toggleClass('opened-group');
          }
        );
      }
    );

  }
);
