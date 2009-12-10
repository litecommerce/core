<form name="modify_form" action="admin.php" method="POST" enctype="multipart/form-data">
<input FOREACH="dialog.allparams,name,val" type="hidden" name="{name}" value="{val}"/>
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
  <td class="FormButton" nowrap>Product Name</td>
  <td class=ProductDetails>
    <input type="text" name="name" size="45" value="{product.name:r}">
  </td>
</tr>  
<tr IF="!xlite.mm.activeModules.MultiCategories">
	<td class="FormButton" nowrap>Category</td>
	<td> 
        <widget class="CCategorySelect" formField="category_id" selectedCategory="{product.categories.0.category_id}">
    </td>
</tr>

<widget module="MultiCategories" class="CCategorySelect" template="modules/MultiCategories/additionalCategories.tpl" product="{product}" allOption formField="category_id">

<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
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
    <td class="FormButton">Meta tags</td>
    <td><input name="meta_tags" value="{product.meta_tags}" size=45></td>
</tr>

<tr>
  <td class=ProductDetails valign=top height="15" width="140"><font class="FormButton">Thumbnail</font><br>(in products list)</td>
  <td class=ProductDetails valign="middle">
<img IF="product.hasThumbnail()" src="cart.php?target=image&action=product_thumbnail&product_id={product.product_id}&_{rand()}" border=0></img>
<br>
<widget class="CImageUpload" field="thumbnail" actionName="images" formName="modify_form" object="{product}">
</td>
</tr>

<tr>
  <td class=ProductDetails valign=top height="15"><font class="FormButton">Image</font><br>(on product details page)</td>
  <td class=ProductDetails valign="middle">
<img IF="product.hasImage()" src="cart.php?target=image&action=product_image&product_id={product.product_id}&_{rand()}" border=0></img>
<br>
<widget class="CImageUpload" field="image" actionName="images" formName="modify_form" object="{product}">
</td>
</tr>

<tr>
  <td valign=middle class="FormButton">Price</td>
  <td valign="middle">
    <input type="text" name="price" size="18" value="{product.price}">
  </td>
</tr>

<tr>
  <td valign=middle><font class="FormButton">Tax class</font><br>
    <i>You can specify tax classes in Settings/Taxes/add rate/condition dialog</i>
  </td>
  <td valign="middle">
    <select name="tax_class">
        <option value="">None</option>
    	<option FOREACH="xlite.factory.TaxRates.productClasses,_tax_class" selected="product.tax_class=_tax_class">{_tax_class}</option>
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
  <td valign="middle" class="FormButton">Available for sale</td>
  <td valign="middle" class=ProductDetails>
   <select name=enabled>
        <option value=1 selected="{isSelected(product,#enabled#,#1#)}">Yes</option>
        <option value=0 selected="{isSelected(product,#enabled#,#0#)}">No</option>
    </select> 
  </td>
</tr>

{*extraFields*}
<widget class="CExtraFields" template="product/extra_fields.tpl" product="{product}">

<widget module="WholesaleTrading" template="modules/WholesaleTrading/memberships/membership_product.tpl">

<tr><td colspan=2>&nbsp;</td></tr>

<tr>
<td>
<input type="submit" value=" Update ">
&nbsp;
<input type="button" value=" Clone " onClick="document.modify_form.action.value='clone'; document.modify_form.submit();">
</td> 

<td>&nbsp;</td>
</tr>

</table>
</form>
