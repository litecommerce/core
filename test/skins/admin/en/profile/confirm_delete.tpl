{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Delete confirmation template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<form action="admin.php" name="confirm_delete_profile" method="post">

  WARNING! There are no more available administrators' profiles. You will not be able to manage your store after you delete this profile.<br>

  <br />

  Are you sure you want to delete this profile?

  <br />
  <br />

  <input type="hidden" foreach="allParams,_name,_value" name="{_name}" value="{_value}" />
  <input type="hidden" name="action" value="delete" />

  <a href="javascript: void();" onclick="javascript: document.confirm_delete_profile.submit();"><img src="skins/admin/en/images/go.gif" width="13" height="13" border="0" align="absmiddle" /> Yes</a>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="javascript: history.go(-1)"><img src="skins/admin/en/images/go.gif" width="13" height="13" border="0" align="absmiddle" /> No</a>

</form>

