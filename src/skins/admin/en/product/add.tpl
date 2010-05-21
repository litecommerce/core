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
<p>Mandatory fields are marked with an asterisk (<font class="Star">*</font>).<br><br>

<table border="0" cellpadding="0">
<form name="modify_form" action="admin.php" method="POST" enctype="multipart/form-data">
<input type="hidden" name="target" value="add_product">
<input type="hidden" name="action" value="add">
<tr>
  <td class="FormButton" nowrap>SKU</td>
  <td class=ProductDetails>
    <input type="text" name="sku" size="20" value="{sku:r}">
  </td>
</tr>
<tr>
  <td>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
  		<td class="FormButton" nowrap width="100%">Product Name</td>
        <td class="Star">&nbsp;*&nbsp;</td>
	</tr>
	</table>
  </td>
  <td class=ProductDetails>
    <input type="text" name="name" size="45" value="{name:r}">
    <widget class="XLite_Validator_RequiredValidator" field="name">
  </td>
</tr>

<tr IF="!xlite.mm.activeModules.MultiCategories">
	<td>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
  		<td class="FormButton" nowrap width="100%">Category</td>
        <td class="Star">&nbsp;*&nbsp;</td>
	</tr>
	</table>
	</td>
    <td>
        <widget class="XLite_View_CategorySelect" fieldName="category_id" selectedCategoryId="{product.categories.0.category_id}">
		<widget class="XLite_Validator_RequiredValidator" field="category_id">
    </td>
</tr>

<widget module="MultiCategories" class="XLite_View_CategorySelect" template="modules/MultiCategories/additionalCategories.tpl" product="{product}" fieldName="category_id" >

<tr>
  <td valign=middle class="FormButton">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
  		<td class="FormButton" nowrap width="100%">Price</td>
        <td class="Star">&nbsp;*&nbsp;</td>
	</tr>
	</table>
  </td>
  <td valign="middle">
    {if:isEmpty(price)}
    <input type="text" name="price" size="18" value="0.00">
    {else:}
    <input type="text" name="price" size="18" value="{price}">
    {end:}
  </td>
</tr>
<tr>

<tbody IF="{config.General.enable_sale_price}">
<tr>
  <td valign=middle class="FormButton">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
  		<td class="FormButton" nowrap width="100%">Market price</td>
        <td>&nbsp;</td>
	</tr>
	</table>
  </td>
  <td valign="middle">
    {if:isEmpty(sale_price)}
    <input type="text" name="sale_price" size="18" value="0.00">
    {else:}
    <input type="text" name="sale_price" size="18" value="{sale_price}">
    {end:}
  </td>
</tr>
<tr>
</tbody>

  <td valign=middle class=Text><font class="FormButton">Weight</font> ({config.General.weight_symbol:h})</td>
  <td valign="middle" IF="!weight">
    <input type="text" name="weight" size="18" value="1.00">
  </td>
  <td valign="middle" IF="weight">
    <input type="text" name="weight" size="18" value="{weight}">
  </td>
</tr>
<tr>
  <td valign=middle class=Text><font class="FormButton">Tax class</font><br>
  <i>You can specify tax classes in<br>
  Settings/Taxes/add rate/condition dialog</i></td>
  <td valign="middle">
    <select name="tax_class"><option value="" selected="tax_class=##">None</option>
	<option FOREACH="xlite.factory.XLite_Model_TaxRates.productClasses,_taxClass" option="{_taxClass}" selected="tax_class=_taxClass">{_taxClass}</option>
	</select>
  </td>
</tr>
<tr>
  <td valign="middle" class="FormButton">Free shipping</td>
  <td valign="middle" class=ProductDetails IF="free_shipping=##">
   <select name=free_shipping>
        <option value=1 >Yes</option>
        <option value=0 selected>No</option>
    </select>
  </td>
  <td valign="middle" class=ProductDetails IF="!free_shipping=##">
   <select name=free_shipping>
        <option value=1 selected="free_shipping=#1#">Yes</option>
        <option value=0 selected="free_shipping=#0#">No</option>
    </select>
  </td>
</tr>

<tr>
  <td>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
  		<td class="FormButton" nowrap width="100%">Available for sale</td>
        <td class="Star">&nbsp;*&nbsp;</td>
	</tr>
	</table>
  </td>
  <td valign="middle" class=ProductDetails IF="enabled=##">
   <select name=enabled>
        <option value=1 selected>Yes</option>
        <option value=0>No</option>
    </select>
  </td>
  <td valign="middle" class=ProductDetails IF="!enabled=##">
   <select name=enabled>
        <option value=1 selected="enabled=#1#">Yes</option>
        <option value=0 selected="enabled=#0#">No</option>
    </select>
  </td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>

<tr>
  <td class=ProductDetails valign=top height="15"><font class="FormButton">Thumbnail</font><br>(in products list)</td>
  <td class=ProductDetails valign="middle">
  <span IF="thumbnail_read_only" class="ErrorMessage">WARNING! File cannot be uploaded!<br>Please check and correct file permissions.<br></span>
  <widget class="XLite_View_ImageUpload" field="thumbnail" actionName="images" formName="modify_form" object="{product}">
  </td>
</tr>

<tr>
  <td class=ProductDetails valign=top height="15"><font class="FormButton">Image</font><br>(on product details page)</td>
  <td class=ProductDetails valign="middle">
  <span IF="image_read_only" class="ErrorMessage">WARNING! File cannot be uploaded!<br>Please check and correct file permissions.<br></span>
  <widget class="XLite_View_ImageUpload" field="image" actionName="images" formName="modify_form" object="{product}">
  </td>
</tr>

<tr>
	<td class="FormButton">Product page title </td>
    <td><input name="meta_title" value="{meta_title}" size=50></td>
</tr>	

<tr>
  <td valign="top" class="FormButton">Brief Description</td>
  <td valign="top" class=ProductDetails>
    <textarea name="brief_description" cols="45" rows="6">{brief_description:h}</textarea>
  </td>
</tr>

<tr>
  <td valign="top" class="FormButton">Detailed Description</td>
  <td valign="top" class=ProductDetails>
    <textarea name="description" cols="45" rows="6">{description:h}</textarea>
  </td>
</tr>

<tr>
    <td valign="top" class="FormButton">Meta keywords</td>
    <td><input name="meta_tags" value="{meta_tags}" size=50></td>
</tr>
<tr>
    <td class="FormButton">Meta description</td>
    <td><input name="meta_desc" value="{meta_desc}" size=50></td>
</tr>
<tr>
    <td valign="top" class="FormButton">Clean URL</td>
    <td><input name="clean_url" value="{clean_url}" size="50" /></td>
</tr>

<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>

<tr>
  <td valign=middle class="FormButton">Pos.</td>
  <td valign="middle">
    {if:isEmpty(order_by)}
    <input type="text" name="order_by" size="5" value="0">
    {else:}
    <input type="text" name="order_by" size="5" value="{order_by}">
    {end:}
  </td>
</tr>

{*extraFields*}
<widget module="GoogleCheckout" template="modules/GoogleCheckout/product/add.tpl">
<widget class="XLite_View_ExtraFields" template="product/extra_fields.tpl" product="{product}">

<widget module="WholesaleTrading" template="modules/WholesaleTrading/memberships/membership_product.tpl">

<tr><td colspan=2>&nbsp;</td></tr>

<tr>
<td colspan=2><input type="submit" value=" Add "></td>
</form>
</table>
