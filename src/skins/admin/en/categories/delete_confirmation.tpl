{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<form name="deleteForm" action="admin.php" method="post">

  <input type="hidden" name="target" value="categories" />
  <input type="hidden" name="action" value="delete" />
  <input type="hidden" name="category_id" value="{category.category_id}" />
  <input type="hidden" name="subcats" value="{getSubcats()}" />

  <table>

    <tr>
      <td colspan="3">
        {t(#The following categories were selected to be removed#)}:
      </td>
    </tr>

    <tr IF="getRequestParamValue(#subcats#)=#1#">
      <td colspan="3">
        {foreach:getSubtree(category.category_id),key,cat}
          <strong>{cat.name}</strong><br />
        {end:}
      </td>
    </tr>

    <tr IF="!getRequestParamValue(#subcats#)=#1#">
      <td colspan="3">
        {foreach:getCategories(category.category_id),key,cat}
        <strong>{cat.name}</strong><br />
        {end:}
      </td>
    </tr>

    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>

    <tr>
      <td colspan="3" class="admin-title">
        {t(#Warning: this operation can not be reverted!#)}
      </td>
    </tr>

    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>

    <tr>
      <td colspan="3">
        {t(#Are you sure you want to continue?#)}
        <br />
        <widget class="\XLite\View\Button\Submit" label="{t(#Yes#)}" style="" />&nbsp;&nbsp;
        <widget class="\XLite\View\Button\Regular" label="{t(#No#)}" style="back-button" jsCode="history.go(-1);" />
      </td>
    </tr>

  </table>

</form>
