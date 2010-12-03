{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * TODO: the View\Model should be used instead
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<p>Mandatory fields are marked with an asterisk (<font class="Star">*</font>).<br><br>

<widget class="XLite\View\Form\Product\Modify\Single" name="modify_form" />

<table border=0 cellpadding="0">
<tr>
  <td width="30%">&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td class="FormButton" nowrap>SKU</td>
  <td class=ProductDetails>
    <input type="text" name="{getNamePostedData(#sku#)}" size="20" value="{product.sku:r}">
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
    <input type="text" name="{getNamePostedData(#name#)}" size="45" value="{product.name:r}">
  </td>
</tr>  
<tr>
	<td>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
  		<td class="FormButton" nowrap width="100%">Category</td>
        <td class="Star">&nbsp;*&nbsp;</td>
	</tr>
	</table>
	</td>
	<td> 
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr><widget class="\XLite\View\FormField\Select\Categories" fieldName="{getNamePostedData(##,#category_ids#)}" value="{product.getCategories()}" /></tr>
    </table>
    </td>
</tr>

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
    <input type="text" name="{getNamePostedData(#price#)}" size="18" value="{product.price}">
  </td>
</tr>
<widget module="CDev\ProductAdviser" template="modules/CDev/ProductAdviser/price_changed.tpl" IF="{priceNotifyPresent}" dialog="{dialog}">

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
    <input type="text" name="{getNamePostedData(#sale_price#)}" size="18" value="{product.sale_price}">
  </td>
</tr>
</tbody>

<tr>
  <td valign=middle><font class="FormButton">Tax class</font><br>
    <i>You can specify tax classes in Settings/Taxes/add rate/condition dialog</i>
  </td>
  <td valign="middle">
    <select name="{getNamePostedData(#tax_class#)}">
        <option value="">None</option>
    	<option FOREACH="xlite.factory.\XLite\Model\TaxRates.productClasses,_tax_class" selected="product.tax_class=_tax_class">{_tax_class}</option>
	</select>
  </td>
</tr>

<tr>
  <td valign=middle class=Text><font class="FormButton">Weight</font> ({config.General.weight_symbol:h})</td>
  <td valign="middle">
    <input type="text" name="{getNamePostedData(#weight#)}" size="18" value="{product.weight}">
  </td>
</tr>
<tr>
  <td valign="middle" class="FormButton">Free shipping</td>
  <td valign="middle" class=ProductDetails>
   <select name="{getNamePostedData(#free_shipping#)}">
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
   <select name="{getNamePostedData(#enabled#)}">
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
    <td class="FormButton">Product page title </td>
	<td><input name="{getNamePostedData(#meta_title#)}" value="{product.meta_title}" size=50></td>
</tr>

<tr>
  <td valign="top" class="FormButton">Brief Description</td>
  <td valign="top" class=ProductDetails>
    <textarea name="{getNamePostedData(#brief_description#)}" cols="45" rows="6">{product.brief_description:h}</textarea>
  </td>
</tr>

<tr>
  <td valign="top" class="FormButton">Detailed Description</td>
  <td valign="top" class=ProductDetails>
    <textarea name="{getNamePostedData(#description#)}" cols="45" rows="6">{product.description:h}</textarea>
  </td>
</tr>

<tr>
    <td class="FormButton">Meta keywords</td>
    <td><input name="{getNamePostedData(#meta_tags#)}" value="{product.meta_tags}" size=50></td>
</tr>
<tr>
    <td class="FormButton">Meta description</td>
    <td><input name="{getNamePostedData(#meta_desc#)}" value="{product.meta_desc}" size=50></td>
</tr>
<tr>
    <td class="FormButton">Clean URL</td>
    <td><input name="{getNamePostedData(#clean_url#)}" value="{product.clean_url}" size="50" /></td>
</tr>

<tr>
    <td class="FormButton">Custom Javascript code</td>
    <td><textarea name="{getNamePostedData(#javascript#)}" cols="45" rows="6">{product.javascript}</textarea></td>
</tr>

{* Will be revised later *}
{*<tr>
  <td valign=middle class="FormButton">Pos.</td>
  <td valign="middle"><input type="text" name="{getNamePostedData(#order_by#)}" size="5" value="{product.order_by}"></td>
</tr>*}

{*extraFields*}
<widget module="CDev\GoogleCheckout" template="modules/CDev/GoogleCheckout/product/info.tpl">
<widget class="\XLite\View\ExtraFields" template="product/extra_fields.tpl" product="{product}">

<widget module="CDev\WholesaleTrading" template="modules/CDev/WholesaleTrading/memberships/membership_product.tpl">
<widget module="CDev\ProductAdviser" template="modules/CDev/ProductAdviser/product.tpl">

{displayViewListContent(#product.modify.childs#)}

<tr><td colspan=2>&nbsp;</td></tr>

<tr>
<td>

<widget class="\XLite\View\Button\Submit" label="Add" IF="isNew()" />
<widget class="\XLite\View\Button\Submit" label="Update" IF="!isNew()" />

{* Will be revised later *}
{*&nbsp;
<input type="button" value=" Clone " onClick="document.modify_form.action.value='clone'; document.modify_form.submit();">*}
</td> 

<td>&nbsp;</td>
</tr>

</table>

<widget name="modify_form" end />
