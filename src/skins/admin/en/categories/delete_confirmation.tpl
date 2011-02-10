{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<form name="deleteForm" action="admin.php" method="post">

  <input type="hidden" name="target" value="categories" />
  <input type="hidden" name="action" value="delete" />
  <input type="hidden" name="category_id" value="{category.category_id}" />
  <input type="hidden" name="subcats" value="{%\XLite\Core\Request::getInstance()->subcats%}" />

  <table border="0">

    <tr>
      <td colspan="3">
        The following categories was selected to be removed:
      </td>
    </tr>

    <tr IF="getRequestParamValue(#subcats#)=#1#">
      <td colspan="3">
        {foreach:getSubtree(category.category_id),key,cat}
        <b>{cat.name}</b><br />
        {end:}
      </td>
    </tr>

    <tr IF="!getRequestParamValue(#subcats#)=#1#">
      <td colspan="3">
        {foreach:getCategories(category.category_id),key,cat}
        <b>{cat.name}</b><br />
        {end:}
      </td>
    </tr>

    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>

    <tr>
      <td colspan="3" class="admin-title">
        Warning: this operation can not be reverted!
      </td>
    </tr>

    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>

    <tr>	
      <td colspan="3">Are you sure you want to continue?<br /><br />
        <widget class="\XLite\View\Button\Submit" label="Yes" style="main-button" />&nbsp;&nbsp;
        <widget class="\XLite\View\Button\Regular" label="No" jsCode="javascript: document.location='admin.php?target=categories&category_id={category.parent.getCategoryId()}'" />
      </td>
    </tr>

  </table>

</form>

