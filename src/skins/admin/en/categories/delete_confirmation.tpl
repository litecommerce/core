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

<form name="deleteForm" action="admin.php" method="post">

  <input type="hidden" name="target" value="categories" />
  <input type="hidden" name="action" value="{deleteMode}" />
  <input type="hidden" name="category_id" value="{category.category_id}" />

  <table border="0">

    <tr>
      <td colspan="3">
        The following categories was selected to be removed:
      </td>
    </tr>

    <tr IF="getRequestParamValue(#subcats#)=#1#">
      <td colspan="3">
        {foreach:getSubcategories(category.category_id),key,cat}
        <img src="images/spacer.gif" height="1" width="{cat.getIndentation(20)}" /><b>{cat.name}</b><br />{end:}
      </td>
    </tr>

    <tr IF="!getRequestParamValue(#subcats#)=#1#">
      <td colspan="3">
        {foreach:getCategories(category.category_id),key,cat}
        <img src="images/spacer.gif" height="1" width="{cat.getIndentation(20)}" /><b>{cat.name}</b><br />{end:}
      </td>
    </tr>

    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>

    <tr>
      <td colspan="3" class="AdminTitle">
        Warning: this operation can not be reverted!
      </td>
    </tr>

    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>

    <tr>	
      <td colspan="3">Are you sure you want to continue?<br><br>
        <input type="submit" value="Yes" class="DialogMainButton" />&nbsp;&nbsp;
        <input type="button" value="No" onclick="javascript: document.location='admin.php?target=categories&category_id={category_id}'" />
      </td>
    </tr>

  </table>

</form>

