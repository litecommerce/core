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

<b>{t(#Note#)}:</b> {t(#Use the navigation bar above this dialog to navigate through the catalog categories#)}.

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

    <tr>
      <td>{t(#Category name#)}</td>
      <td class="star">*</td>
      <td>
        <input type="text" name="name" value="{category.name}" size="50" maxlength="255" />
        &nbsp;<span IF="!valid" class="validate-error-message">&lt;&lt; {t(#Required field#)}</span>
      </td>
    </tr>

    <tr>
      <td valign="top">{t(#Category page title#)}</td>
      <td>&nbsp;</td>
      <td>
        <select name="show_title">
          <option value="1" selected="{category.show_title=#1#}">{t(#Use the category name#)}</option>
          <option value="0" selected="{category.show_title=#0#}">{t(#Hide#)}</option>
        </select>
    </tr>


    <tr>
      <td valign="top">{t(#Description#)}</td>
      <td>&nbsp;</td>
      <td><widget class="\XLite\View\FormField\Textarea\Advanced" fieldName="description" cols="50" rows="10" value="{category.description}" /></td>
    </tr>

    <tr IF="hasImage()">
      <td valign="top">
        {t(#Image#)}
      </td>
      <td>&nbsp;</td>
      <td>

        <img IF="category.hasImage()" src="{category.image.getURL()}" alt="" />
        <img IF="!category.hasImage()" src="images/no_image.png" alt="" />
        <br />
        <widget
          class="\XLite\View\Button\FileSelector"
          label="Image upload"
          object="category"
          objectId="{category.getCategoryId()}"
          fileObject="image" />

      </td>
    </tr>

    <tr IF="!getRootCategoryId()=category.getCategoryId()">
      <td>{t(#Membership#)}</td>
      <td class="star">*</td>
      <td>
        <widget class="\XLite\View\MembershipSelect" template="common/select_membership.tpl" field="membership_id" value="{category.membership}" />
      </td>
    </tr>

    <tr IF="!getRootCategoryId()=category.getCategoryId()">
      <td>{t(#Availability#)}</td>
      <td class="star">*</td>
      <td>
        <select name="enabled">
          <option value="1" selected="{category.enabled=#1#}">{t(#Enabled#)}</option>
          <option value="0" selected="{category.enabled=#0#}">{t(#Disabled#)}</option>
        </select>
      </td>
    </tr>

    <tr>
      <td>{t(#HTML title ('title' tag)#)}</td>
      <td>&nbsp;</td>
      <td><input type="text" name="meta_title" value="{category.meta_title}" size="50" /></td>
    </tr>

    <tr>
      <td>{t(#Meta keywords#)}</td>
      <td>&nbsp;</td>
      <td><input type="text" name="meta_tags" value="{category.meta_tags}" size="50" /></td>
    </tr>

    <tr>
      <td>{t(#Meta description#)}</td>
      <td>&nbsp;</td>
      <td><input type="text" name="meta_desc" value="{category.meta_desc}" size="50" /></td>
    </tr>

    <tr IF="!getRootCategoryId()=category.getCategoryId()">
      <td>{t(#Clean URL#)}</td>
      <td>&nbsp;</td>
      <td><input type="text" name="clean_url" value="{category.clean_url}" size="50" /></td>
    </tr>

    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>

    <tr>
      <td colspan="3">
        <widget IF="category.getCategoryId()" class="\XLite\View\Button\Submit" label="Update" />
        <widget IF="!category.getCategoryId()" class="\XLite\View\Button\Submit" label="Create category" />
      </td>
    </tr>

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
