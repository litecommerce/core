/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

function OrderEventDetails()
{
  jQuery(this.base).each(function (index, elem) {
    var $elem = jQuery(elem);

    jQuery('.action', $elem)
    .bind('click', function(event) {
      jQuery('*', $elem).trigger('toggle-action');
    })
    .bind('toggle-action', function() {
      jQuery(this).toggleClass('show-details');
    });


    jQuery('.details', $elem).bind('toggle-action', function() {
      jQuery(this).toggleClass('show-details');
    });
  });
}

OrderEventDetails.prototype.base = 'li.event';

core.autoload('OrderEventDetails');