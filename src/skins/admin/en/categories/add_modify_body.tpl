{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Add/Modify category template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

{*TODO: refactor it.*}

<div class="right-panel">
  <widget class="\XLite\View\EditorLanguageSelector" />
</div>

<p>

<span IF="getRequestParamValue(#mode#)!=#modify#">{t(#Mandatory fields are marked with an asterisk#)} (<span class="star">*</span>).<br /><br /></span>

<b>{t(#Note#)}:</b> {t(#Use navigation bar above this dialog to navigate through the catalog categories.#)}

<hr />

<p IF="message=#updated#"><span class="success-message">&gt;&gt;&nbsp;{t(#Category has been updated successfully#)}&nbsp;&lt;&lt;</span></p>

<p IF="message=#added#"><span class="success-message">&gt;&gt;&nbsp;{t(#Category has been added successfully#)}&nbsp;&lt;&lt;</span></p>

<p IF="!valid"><span class="error-message">&gt;&gt;&nbsp;{t(#There are errors in the form. Category has not been added#)}&nbsp;&lt;&lt;</span></p>

<p>

<form name="add_modify_form" action="admin.php" method="post">

  <input type="hidden" name="target" value="category" />
  <input type="hidden" name="action" value="{getRequestParamValue(#mode#)}" />
  <input type="hidden" name="mode" value="{getRequestParamValue(#mode#)}" />
  <input type="hidden" name="category_id" value="{category_id}" />

  <table width="100%">

    {displayViewListContent(#category.modify.list#)}

  </table>

</form>

{* TODO: restore it

{if:category.category_id&!getRootCategoryId()=category.getCategoryId()}
<br /><br /><br />

<b>Change category location</b>
<hr />

<br />

Not available right now

<form name="move_form" action="admin.php" method="post">

  <input type="hidden" name="target" value="categories" />
  <input type="hidden" name="action" value="move_after" />
  <input type="hidden" name="category_id" value="{category_id}" />

  <table width="100%">

    <tr>
      <td style="width:15%;">Select category:</td>
      <td style="width:85%;">
        <widget class="\XLite\View\CategorySelect" fieldName="moveTo" currentCategoryId={category.category_id} ignoreCurrentPath rootOption />
      </td>
    </tr>

    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>

    <tr>
      <td colspan="3">
        <widget class="\XLite\View\Button\Regular" label="Move after selected" jsCode="javascript:document.forms['move_form'].submit();" />
        &nbsp;&nbsp;&nbsp;
        <widget class="\XLite\View\Button\Regular" label="Make as child of selected" jsCode="javascript:document.forms['move_form'].elements['action'].value='move_as_child';document.forms['move_form'].submit();" />
      </td>
    </tr>

  </table>

</form>
{end:}
*}
