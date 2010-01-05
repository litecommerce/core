<tr>
	<td>{if:category.hasSmallImage()}<img src="cart.php?target=image&action=category_small&category_id={category.category_id}&_{rand()}" border="0">{else:}<img src="images/no_image.gif" border="0">{end:}</td>
	<td>&nbsp;</td>
	<td width="100%" valign="top" rowspan=2>
		<input type="checkbox" name="smallimage_auto" value=1 {if:!xlite.gdlib_enabled}disabled{end:} {if:category.smallimage_auto&xlite.gdlib_enabled}checked{end:} onClick="this.blur();document.getElementById('smallimage_section').style.display = (this.checked) ? 'none' : '';">Generate "Small Icon" from category "Icon"
		<div id="smallimage_section" {if:category.smallimage_auto&xlite.gdlib_enabled}style='display: none;'{end:}>
		{if:xlite.gdlib_enabled}
		<div id="browse_smallimage_section" {*style='display: none;'*}>
		<br>
		<widget class="XLite_View_ImageUpload" field="smallimage" actionName="small_icon" formName="add_modify_form" object="{category}">
		</div>
		{else:}
		<font class="ErrorMessage" IF="!xlite.gdlib_enabled">&nbsp;GDlib is disabled or its version is lower than 2.0.</font>
		<p>
		<widget class="XLite_View_ImageUpload" field="smallimage" actionName="small_icon" formName="add_modify_form" object="{category}">
		{end:}
		</div>
	</td>
</tr>
<tr>
	<td class="FormButton" valign="top">Small Icon</td>
	<td>&nbsp;</td>
</tr>
