/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Address dialog controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id: e3e67d55a9dcb5b8033e2485a05aab24ffeb384d $
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

// Open Delete address dialog
function openDeleteProfile(link)
{
  var url = 'cart.php?target=profile&mode=delete&widget=\\XLite\\View\\Account\\Delete';

  loadDialogByLink(link, url, {width: 600});

  return false;
}
