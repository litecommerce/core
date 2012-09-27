/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Order statuses selector controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */
jQuery(document).ready(function(){
  jQuery('a.popup-warning').map(function() {
    attachTooltip(this, jQuery(this).next('div.status-warning-content').html());
  });
});
