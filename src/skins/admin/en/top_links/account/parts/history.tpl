{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Login history link
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="top_links.account", weight="300")
 *}

<li IF="checkLoginHistoryACL()"><a href="{buildURL(#recent_login#)}">{t(#Login history#)}</a></li>
