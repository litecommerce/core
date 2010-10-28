{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Logged profile block
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="logged">
  <span>{t(#Greetings, X#,_ARRAY_(#name#^getProfileUsername()))}</span>
  <a href="{getProfileURL()}" class="view-profile">{t(#View profile#)}</a>
  <a href="{getLogoffURL()}" class="logoff">{t(#Log out#)}</a>
</div>
