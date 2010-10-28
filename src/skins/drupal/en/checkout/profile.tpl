{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Profile block
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="profile">
  {if:isAnonymous()}
    <widget template="checkout/profile/anonymous.tpl" />
  {else:}
    <widget template="checkout/profile/logged.tpl" />
  {end:}
</div>
