/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Price notification link
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */
// Create form and submit
function notifyMe(link, box)
{
    if (!link || !box || !box.length) {
        return false;
    }

    return !popup.load(link);
}
