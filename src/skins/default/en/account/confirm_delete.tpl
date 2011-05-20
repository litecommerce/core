{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Delete confirmation template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<form action="cart.php" name="confirm_delete_profile" method="post">

  Are you sure you want to delete your profile?

  <br />
  <br />

  <input type="hidden" foreach="allParams,_name,_value" name="{_name}" value="{_value}" />
  <input type="hidden" name="action" value="delete" />

  <a href="javascript: void();" onclick="javascript: document.confirm_delete_profile.submit();"><img src="skins/admin/en/images/go.gif" width="13" height="13" align="absmiddle" alt="" /> Yes</a>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="javascript: history.go(-1)"><img src="skins/admin/en/images/go.gif" width="13" height="13" align="absmiddle" alt="" /> No</a>

</form>

