{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<widget target="download" mode="file_access_denied" template="common/dialog.tpl" head="Access denied" body="modules/Egoods/file_access_denied.tpl">
<widget target="download" mode="file_not_found" template="common/dialog.tpl" head="File not found" body="modules/Egoods/file_not_found.tpl">
<widget target="product" template="common/dialog.tpl" head="Download this product for free" body="modules/Egoods/free_downloads.tpl" IF="getProduct()&product.isFreeForMembership(cart.profile.membership)"/>
