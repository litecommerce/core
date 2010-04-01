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

<p>

<span IF="mode=#add#">Mandatory fields are marked with an asterisk (<font class="Star">*</font>).<br /><br /></span>

<b>Note:</b> Use navigation bar above this dialog to navigate through the catalog categories.

<hr />

<p IF="message=#updated#"><font class="SuccessMessage">&gt;&gt;&nbsp;Category has been updated successfully&nbsp;&lt;&lt;</font></p>

<p IF="message=#added#"><font class="SuccessMessage">&gt;&gt;&nbsp;Category has been added successfully&nbsp;&lt;&lt;</font></p>

<p IF="!valid"><font class="ErrorMessage">&gt;&gt;&nbsp;There are errors in the form. Category has not been added&nbsp;&lt;&lt;</font></p>

<p>

<widget module="FlyoutCategories" template="modules/FlyoutCategories/categories_auto.tpl" />

<form name="add_modify_form" action="admin.php" method="POST" enctype="multipart/form-data">

  <input type="hidden" name="target" value="category" />
  <input type="hidden" name="action" value="{getRequestParamValue(#mode#)}" />
  <input type="hidden" name="mode" value="{getRequestParamValue(#mode#)}" />
  <input type="hidden" name="category_id" value="{category_id}" />
 
  <table border="0" width="100%">

    <tr>
  		<td class="FormButton">Category&nbsp;name</td>
      <td class=Star>*</td>
   		<td><input name="name" value="{name}">
        &nbsp;<font IF="!valid" class="ValidateErrorMessage">&lt;&lt; Required field</font>
      </td>
    </tr>

    <tr>
      <td class="FormButton" nowrap>Parent category</td>
      <td class=Star>*</td>
      <td>{if:mode=#add#}<widget class="XLite_View_CategorySelect" fieldName="parent" selectedCategoryId="{category_id}" rootOption />{else:}<widget class="XLite_View_CategorySelect" selectedCategoryId="{parent}" fieldName="parent" rootOption currentCategoryId="{category_id}" ignoreCurrentPath />{end:}</td>
    </tr>

  	<tr>
  		<td class="FormButton">Membership</td>
      <td class=Star>*</td>
	  	<td>
        <widget class="XLite_View_MembershipSelect" template="common/select_membership.tpl" field="membership" allOption />
  		</td>	
  	</tr>

  	<tr>
  		<td class="FormButton">Availability</td>
      <td class=Star>*</td>
	  	<td>
		  	<select name="enabled">
    			<option value="1" selected="{enabled=#1#}">Enabled</option>
    			<option value="0" selected="{enabled=#0#}">Disabled</option>
  			</select>
	  	</td>
    </tr>	

    <tr>
  		<td>{if:category.hasImage()}<img src="cart.php?target=image&action=category&category_id={category.category_id}&_{rand()}" border="0" />{else:}<img src="images/no_image.gif" border="0" />{end:}</td>
      <td>&nbsp;</td>
  		<td width="100%" valign="bottom" rowspan=2>
        <widget class="XLite_View_ImageUpload" field="image" actionName="icon" formName="add_modify_form" object="{category}" />
  		</td>
    </tr>

  	<tr>
	    <td class="FormButton" valign="top">Icon</td>
      <td>&nbsp;</td>
    </tr>

    <widget module="FlyoutCategories" template="modules/FlyoutCategories/add_modify_body.tpl" />

    <tr>
      <td class="FormButton">Category page title </td>
      <td>&nbsp;</td>
      <td><input name="meta_title" value="{meta_title}" size=50 /></td>
    </tr>

    <tr>
  		<td class="FormButton" valign="top">Description</td>
      <td>&nbsp;</td>
	    <td><textarea name="description" cols="50" rows="10">{description}</textarea></td>
  	</tr>

    <tr>
      <td class="FormButton">Meta keywords</td>
      <td>&nbsp;</td>
      <td><input name="meta_tags" value="{meta_tags}" size=50 /></td>
    </tr>

    <tr>
      <td class="FormButton">Meta description</td>
      <td>&nbsp;</td>
      <td><input name="meta_desc" value="{meta_desc}" size=50 /></td>
    </tr>

  	<tr>
	  	<td class="FormButton">Pos.</td>
      <td>&nbsp;</td>
		  <td><input name="order_by" value="{order_by}" size="3" /></td>
    </tr>

    <tr>
      <td colspan=3>&nbsp;</td>
    </tr>

    <tr>
      <td colspan=3>
        <input IF="getRequestParamValue(#mode#)=#add#" type="submit" value="Add" />
        <input IF="getRequestParamValue(#mode#)=#modify#" type="submit" value="Update" />
      </td>
    </tr>

</table>

</form>

