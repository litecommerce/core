<p class="ErrorMessage" IF="!product.detailedImages">There are no detailed images for this product</p>

<p>
<FORM IF="product.detailedImages" action="admin.php" name="images_form" method="POST">
<input FOREACH="dialog.allparams,name,val" type="hidden" name="{name}" value="{val}"/>
<input type="hidden" name="action" value="update_detailed_images">
<input type="hidden" name="image_id" value="">

<span FOREACH="product.detailedImages,id,image" class="Text">
<p><font class="AdminHead">Detailed image #{inc(id)}</font><br>
<b>Note:</b> Image border will not be displayed in customer's frontend
</p>
<img src="{image.imageURL}" border=0 style="border: 1px solid #B2B2B3"><br>
<p>
<table border=0>
<tr>
	<td align="right">
		Alternative text: 
	</td>
	<td>
		<input type="text" name="alt[{image.image_id}]" value="{image.alt:r}" size="55">
	</td>
</tr>
<tr>
	<td align="right">
		Position: 
	</td>
	<td>
		<input type="text" name="order_by[{image.image_id}]" value="{image.order_by:r}" size=3>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>
        <input type="submit" value=" Update ">
        &nbsp;
		<input type="button" Value=" Delete the image " onClick="images_form.image_id.value='{image.image_id}';images_form.action.value='delete_detailed_image';images_form.submit()">
	</td>
</tr>
</table>
<p><br>
</span>

</FORM>

<p>
<br><br>
<FORM action="admin.php" method="POST" name="imageForm" enctype="multipart/form-data">
<input FOREACH="dialog.allparams,name,val" type="hidden" name="{name}" value="{val}"/>
<input type="hidden" name="action" value="add_detailed_image">

<table border=0 cellspacing=3 cellpadding=0>
<tr>
    <td colspan=2 valign="top" class="AdminTitle">Add Image</td>
</tr>
<tr>
    <td colspan=2>&nbsp;</td>
</tr>
<tr>
	<td>Alternative text:</td>
	<td><input type="text" name="alt" size=55></td>
</tr>
<tr>
	<td>Position:</td>
	<td><input type="text" name="order_by" size=3 value="1"></td>
</tr>
<tr>	
	<td valign=top>Image file:</td>
	<td valign="middle">
        <widget class="CImageUpload" field="image" actionName="add_detailed_image" formName="imageForm">
	</td>
</tr>
<tr>
	<td colspan=2><input type="submit" value=" Add "></td>
</tr>	
</table>

</FORM>
