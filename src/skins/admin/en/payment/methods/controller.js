/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Payment methods list controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

function makeSmallHeightPMT(button)
{
  switchHeightPMT(jQuery(button).parents('td:eq(0)').find('textarea'));
}

function makeLargeHeightPMT(button)
{
  switchHeightPMT(jQuery(button).parents('td:eq(0)').find('textarea'));
}

function switchHeightPMT(area)
{
  var max = "126px";

  if ("undefined" === typeof(area.attr("old_height"))) {
    jQuery(area).attr("old_height", area.css("height"));
  }

  if (max === area.css("height")) {
    area.css("height", area.attr("old_height"));

  } else {
    area.css("height", max);
  }
}

