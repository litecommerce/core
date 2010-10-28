{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Add/Modify category template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<div class="right-panel">
  <widget class="\XLite\View\EditorLanguageSelector" />
</div>

<p>

<span IF="getRequestParamValue(#mode#)!=#modify#">Mandatory fields are marked with an asterisk (<font class="Star">*</font>).<br /><br /></span>

<b>Note:</b> Use navigation bar above this dialog to navigate through the catalog categories.

<hr />

<p IF="message=#updated#"><font class="SuccessMessage">&gt;&gt;&nbsp;Category has been updated successfully&nbsp;&lt;&lt;</font></p>

<p IF="message=#added#"><font class="SuccessMessage">&gt;&gt;&nbsp;Category has been added successfully&nbsp;&lt;&lt;</font></p>

<p IF="!valid"><font class="ErrorMessage">&gt;&gt;&nbsp;There are errors in the form. Category has not been added&nbsp;&lt;&lt;</font></p>

<p>

<form name="add_modify_form" action="admin.php" method="post" enctype="multipart/form-data">

  <input type="hidden" name="target" value="category" />
  <input type="hidden" name="action" value="{getRequestParamValue(#mode#)}" />
  <input type="hidden" name="mode" value="{getRequestParamValue(#mode#)}" />
  <input type="hidden" name="category_id" value="{category_id}" />
 
  <table border="0" width="100%">

    <tr>
      <td width="15%" class="FormButton">Category&nbsp;name</td>
      <td class="Star">*</td>
      <td width="85%">
        <input name="name" value="{category.name}" size="50" maxlength="255" />
        &nbsp;<font IF="!valid" class="ValidateErrorMessage">&lt;&lt; Required field</font>
      </td>
    </tr>

    <tr>
      <td class="FormButton" valign="top">Category page title</td>
      <td>&nbsp;</td>
      <td>
        <select name="show_title">
          <option value="1" selected="{category.show_title=#1#}">Use the category name</option>
          <option value="0" selected="{category.show_title=#0#}">Hide</option>
        </select>
    </tr>


    <tr>
      <td class="FormButton" valign="top">Description</td>
      <td>&nbsp;</td>
      <td><textarea name="description" cols="50" rows="10">{category.description}</textarea></td>
    </tr>

    <tr>
      <td>{if:category.hasImage()}<img src="{category.image.getURL()}" border="0" />{else:}<img src="images/no_image.png" border="0" />{end:}</td>
      <td>&nbsp;</td>
      <td valign="bottom" rowspan=2>
      <widget class="\XLite\View\ImageUpload" field="image" actionName="icon" formName="add_modify_form" object="{category.image}" />
      </td>
    </tr>

    <tr>
      <td class="FormButton" valign="top">Image</td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td class="FormButton">Membership</td>
      <td class="Star">*</td>
      <td>
        <widget class="\XLite\View\MembershipSelect" template="common/select_membership.tpl" field="membership_id" value="{category.membership_id}" />
      </td>  
    </tr>

    <tr>
      <td class="FormButton">Availability</td>
      <td class="Star">*</td>
      <td>
        <select name="enabled">
          <option value="1" selected="{category.enabled=#1#}">Enabled</option>
          <option value="0" selected="{category.enabled=#0#}">Disabled</option>
        </select>
      </td>
    </tr>  

    <tr>
      <td class="FormButton">HTML title ('title' tag)</td>
      <td>&nbsp;</td>
      <td><input name="meta_title" value="{category.meta_title}" size="50" /></td>
    </tr>

    <tr>
      <td class="FormButton">Meta keywords</td>
      <td>&nbsp;</td>
      <td><input name="meta_tags" value="{category.meta_tags}" size="50" /></td>
    </tr>

    <tr>
      <td class="FormButton">Meta description</td>
      <td>&nbsp;</td>
      <td><input name="meta_desc" value="{category.meta_desc}" size="50" /></td>
    </tr>

    <tr>
      <td class="FormButton">Clean URL </td>
      <td>&nbsp;</td>
      <td><input name="clean_url" value="{category.clean_url}" size="50" /></td>
    </tr>

    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>

    <tr>
      <td colspan="3">
        <input type="submit" {if:getRequestParamValue(#mode#)=#modify#} value="Update" {else:} value="Create category" {end:} />
      </td>
    </tr>

  </table>

</form>

{if:category.category_id}
<br /><br /><br />

<b>Change category location</b>
<hr />

<br />

Not available right now

{*<form name="move_form" action="admin.php" method="post">

  <input type="hidden" name="target" value="categories" />
  <input type="hidden" name="action" value="move_after" />
  <input type="hidden" name="category_id" value="{category_id}" />

  <table border="0" width="100%">

    <tr>
      <td width="15%" class="FormButton">Select category:</td>
      <td width="85%">
        <widget class="\XLite\View\CategorySelect" fieldName="moveTo" currentCategoryId={category.category_id} ignoreCurrentPath rootOption />
      </td>
    </tr>

    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>

    <tr>
      <td colspan="3">
        <input type="button" onclick="javascript:document.forms['move_form'].submit();" value="Move after selected" />
        &nbsp;&nbsp;&nbsp;
        <input type="button" onclick="javascript:document.forms['move_form'].elements['action'].value='move_as_child';document.forms['move_form'].submit();" value="Make as child of selected" />
      </td>
    </tr>

  </table>

</form>*}
{end:}

