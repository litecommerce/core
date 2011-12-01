/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Modified files list controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

function makeSmallHeight(button)
{
  switchHeight('.modified-files-block');
}

function makeLargeHeight(button)
{
  switchHeight('.modified-files-block');
}

function switchHeight(area)
{
  var max = "600px";

  if ("undefined" === typeof(jQuery(area).attr("old_height"))) {
    jQuery(area).attr("old_height", jQuery(area).css("height"));
  }

  if (max === jQuery(area).css("height")) {
    jQuery(area).css("height", jQuery(area).attr("old_height"));
  } else {
    jQuery(area).css("height", max);
  }
}

function attachClickOnSelectAll() {
  jQuery('a.select-all').each(function () {
    jQuery(this).click(function () {
      jQuery('.modified-file input[type=checkbox]').attr('checked', 'checked');
    });
  });
}

function attachClickOnUnselectAll() {
  jQuery('a.unselect-all').each(function () {
    jQuery(this).click(function () {
      jQuery('.modified-file input[type=checkbox]').attr('checked', '');
    });
  });
}

core.bind(
  'load',
  function () {
    jQuery('#radio-select-all').click(function () {
      jQuery('.modified-file input[type=checkbox]')
      .attr('checked', '')
      .attr('readonly', 'readonly')
      .addClass('readonly');

      jQuery('a.unselect-all, a.select-all').unbind('click');
    });

    jQuery('#radio-unselect').click(function () {
      attachClickOnSelectAll();
      attachClickOnUnselectAll();

      jQuery('.modified-file input[type=checkbox]')
      .removeAttr('readonly')
      .removeClass('readonly');
    });

    attachClickOnSelectAll();

    attachClickOnUnselectAll();

    // Must be selected by default
    jQuery('#radio-select-all').click();
  }
  );
