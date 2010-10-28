{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Create proile selector
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="checkout.profile.create", weight="20")
 *}
<div class="selector">
  <input type="hidden" name="create_profile" value="0" />
  <input type="checkbox" id="create_profile_chk" name="create_profile" value="1" checked="{isSeparateProfile()}" />
  <label for="create_profile_chk">{t(#Create an account for later use#)}</label>
</div>
