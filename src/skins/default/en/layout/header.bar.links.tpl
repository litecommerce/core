{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Header bar account links
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="layout.header.bar", weight="100")
 *}
<ul class="account-links inline">
  <li class="account-link-1 first"><a href="{buildURL(#login#)}" class="log-in">{t(#Log in#)}</a></li>
  <li class="account-link-2 last"><a href="{buildURL(#profile#,##,_ARRAY_(#mode#^#register#))}" class="register">{t(#Register#)}</a></li>
</ul>
