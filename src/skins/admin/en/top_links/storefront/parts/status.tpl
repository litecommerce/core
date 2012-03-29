{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Storefront status info
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="top_links.storefront", weight="100")
 *}

{*** TODO: use actual status, when implemented ***}
<li IF="!getCustomerZoneWarning()"><a class="text frontend-opened">{t(#Store is opened#)}</a></li>
<li IF="getCustomerZoneWarning()"><a class="text frontend-closed">{t(#Store is closed#)}</a></li>
