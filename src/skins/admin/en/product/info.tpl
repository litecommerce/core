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

<form name="modify_form" action="admin.php" method="POST" enctype="multipart/form-data">
<input FOREACH="allparams,name,val" type="hidden" name="{name}" value="{val}"/>
<input type="hidden" name="action" value="info">

<table border=0 cellpadding="0">
<tr>
  <td width="30%">&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td class="FormButton" nowrap>SKU</td>
  <td class=ProductDetails>
    <input type="text" name="sku" size="20" value="{product.sku:r}">
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
    <input type="text" name="name" size="45" value="{product.name:r}">
  </td>
</tr>  
<tr IF="!mm.isModuleActive(#MultiCategories#)">
	<td>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
  		<td class="FormButton" nowrap width="100%">Category</td>
        <td class="Star">&nbsp;*&nbsp;</td>
	</tr>
	</table>
	</td>
	<td> 
        <widget class="\XLite\View\CategorySelect" fieldName="category_id" selectedCategoryId="{product.categories.0.category_id}">
		<widget class="\XLite\Validator\RequiredValidator" field="category_id">
    </td>
</tr>

<widget module="MultiCategories" class="\XLite\View\CategorySelect" template="modules/MultiCategories/additionalCategories.tpl" product="{product}" allOption fieldName="category_id">

<tr>
  <td>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
  		<td class="FormButton" nowrap width="100%">Price</td>
        <td class="Star">&nbsp;*&nbsp;</td>
	</tr>
	</table>
  </td>
  <td valign="middle">
    <input type="text" name="price" size="18" value="{product.price}">
  </td>
</tr>
<widget module="ProductAdviser" template="modules/ProductAdviser/price_changed.tpl" visible="{priceNotifyPresent}" dialog="{dialog}">

<tbody IF="{config.General.enable_sale_price}">
<tr>
  <td>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
  		<td class="FormButton" nowrap width="100%">Market price</td>
        <td>&nbsp;</td>
	</tr>
	</table>
  </td>
  <td valign="middle">
    <input type="text" name="sale_price" size="18" value="{product.sale_price}">
  </td>
</tr>
</tbody>

<tr>
  <td valign=middle><font class="FormButton">Tax class</font><br>
    <i>You can specify tax classes in Settings/Taxes/add rate/condition dialog</i>
  </td>
  <td valign="middle">
    <select name="tax_class">
        <option value="">None</option>
    	<option FOREACH="xlite.factory.\XLite\Model\TaxRates.productClasses,_tax_class" selected="product.tax_class=_tax_class">{_tax_class}</option>
	</select>
  </td>
</tr>

<tr>
  <td valign=middle class=Text><font class="FormButton">Weight</font> ({config.General.weight_symbol:h})</td>
  <td valign="middle">
    <input type="text" name="weight" size="18" value="{product.weight}">
  </td>
</tr>
<tr>
  <td valign="middle" class="FormButton">Free shipping</td>
  <td valign="middle" class=ProductDetails>
   <select name=free_shipping>
        <option value=1 selected="{isSelected(product,#free_shipping#,#1#)}">Yes</option>
        <option value=0 selected="{isSelected(product,#free_shipping#,#0#)}">No</option>
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
  <td valign="middle" class=ProductDetails>
   <select name=enabled>
        <option value=1 selected="{isSelected(product,#enabled#,#1#)}">Yes</option>
        <option value=0 selected="{isSelected(product,#enabled#,#0#)}">No</option>
    </select> 
  </td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>

<tr>
  <td class=ProductDetails valign=top height="15" width="140"><font class="FormButton">Thumbnail</font><br>(in products list)</td>
  <td class=ProductDetails valign="middle">
<img IF="product.hasThumbnail()" src="{product.thumbnailURL:h}" border=0></img>
<br>
<span IF="thumbnail_read_only" class="ErrorMessage">WARNING! File cannot be uploaded!<br>Please check and correct file permissions.<br></span>
<widget class="\XLite\View\ImageUpload" field="thumbnail" actionName="images" formName="modify_form" object="{product}">
</td>
</tr>

<tr>
  <td class=ProductDetails valign=top height="15"><font class="FormButton">Image</font><br>(on product details page)</td>
  <td class=ProductDetails valign="middle">
<img IF="product.hasImage()" src="{product.imageURL:h}" border=0></img>
<br>
<span IF="image_read_only" class="ErrorMessage">WARNING! File cannot be uploaded!<br>Please check and correct file permissions.<br></span>
<widget class="\XLite\View\ImageUpload" field="image" actionName="images" formName="modify_form" object="{product}">
</td>
</tr>

<tr>
    <td class="FormButton">Product page title </td>
	<td><input name="meta_title" value="{product.meta_title}" size=50></td>
</tr>

<tr>
  <td valign="top" class="FormButton">Brief Description</td>
  <td valign="top" class=ProductDetails>
    <textarea name="brief_description" cols="45" rows="6">{product.brief_description:h}</textarea>
  </td>
</tr>

<tr>
  <td valign="top" class="FormButton">Detailed Description</td>
  <td valign="top" class=ProductDetails>
    <textarea name="description" cols="45" rows="6">{product.description:h}</textarea>
  </td>
</tr>

<tr>
    <td class="FormButton">Meta keywords</td>
    <td><input name="meta_tags" value="{product.meta_tags}" size=50></td>
</tr>
<tr>
    <td class="FormButton">Meta description</td>
    <td><input name="meta_desc" value="{product.meta_desc}" size=50></td>
</tr>
<tr>
    <td class="FormButton">Clean URL</td>
    <td><input name="clean_url" value="{product.clean_url}" size="50" /></td>
</tr>
<tr>
  <td valign=middle class="FormButton">Pos.</td>
  <td valign="middle"><input type="text" name="order_by" size="5" value="{product.order_by}"></td>
</tr>

{*extraFields*}
<widget module="GoogleCheckout" template="modules/GoogleCheckout/product/info.tpl">
<widget class="\XLite\View\ExtraFields" template="product/extra_fields.tpl" product="{product}">

<widget module="WholesaleTrading" template="modules/WholesaleTrading/memberships/membership_product.tpl">
<widget module="ProductAdviser" template="modules/ProductAdviser/product.tpl">

{displayViewListContent(#product.modify.childs#)}

<tr><td colspan=2>&nbsp;</td></tr>

<tr>
<td>
<input type="submit" value=" Update " class="DialogMainButton">
&nbsp;
<input type="button" value=" Clone " onClick="document.modify_form.action.value='clone'; document.modify_form.submit();">
</td> 

<td>&nbsp;</td>
</tr>

</table>
</form>
