/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Address dialog controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

// Open Modify address dialog
function openModifyAddress(link, id)
{
  var url = 'cart.php?target=address_book&address_id=' + id + '&widget=\\XLite\\View\\Address\\Modify';

  loadDialogByLink(link, url, {width: 600});

  return false;
}

// Open Add address dialog
function openAddAddress(link, id)
{
  var url = 'cart.php?target=address_book&profile_id=' + id + '&widget=\\XLite\\View\\Address\\Modify';

  loadDialogByLink(link, url, {width: 600});

  return false;
}

// Open Delete address dialog
function openDeleteAddress(link, id)
{
  var url = 'cart.php?target=address_book&address_id=' + id + '&widget=\\XLite\\View\\Address\\Delete';

  loadDialogByLink(link, url, {width: 600});

  return false;
}
