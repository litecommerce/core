{* Horizontal subcategory menu body *}

{if:data.scatCounter=#1#}
<script type="text/javascript" language="JavaScript" src="{data.catalogPath}/body_footer.js"></script>
{end:}

{if:!data.scheme.options.color.value=#blue#}
<script type="text/javascript" language="JavaScript">
if (typeof(parents) != "undefined") {
{foreach:data.items,item}
parents[{item.category.category_id}] = Array();
{foreach:item.path_ids,itemIndex,v}
parents[{item.category.category_id}][{itemIndex}] = {v};
{end:}
cat_ids[{item.category.category_id}] = Array();
cat_ids[{item.category.category_id}][0] = '{item.is_last}';
cat_ids[{item.category.category_id}][1] = '{item.is_first}';
{if:!item.number}cat_ids[{item.category.category_id}][2] = 0;{end:}
{if:item.number}cat_ids[{item.category.category_id}][2] = 1;{end:}
{end:}
}
</script>
{end:}

<div id="submenu_{data.parent}" style="visibility: hidden; position: absolute; left: 0; top: 0;">
<table border=0 cellpadding=0 cellspacing=0>
<tr>

{if:data.scheme.options.color.value=#blue#}
	<td class="FlyoutBox"> 
		<div FOREACH="data.items,item">
<script type="text/javascript" language="JavaScript">var catHref='cart.php?target=category&category_id={item.category.category_id}'; var catHref_{item.category.category_id}=catHref;</script>
			<div id="menu_{item.category.category_id}" style="position: relative; cursor: pointer;" onclick="gotoCategory(catHref_{item.category.category_id});">

{if:!item.number}
<table border=0 cellpadding=0 cellspacing=0 width="100%">
	<tr>
		<td id="td2_{item.category.category_id}" class="SubMenuOut" onMouseOver="if (!isRealOverAction(event, this)) return; show_menu(0, '{data.parent}'); change_color_{data.scheme.options.color.value}('{item.category.category_id}', over);" onMouseOut="if (!isRealOutAction(event, this)) return; change_color_{data.scheme.options.color.value}('{item.category.category_id}', out); close_menu();">
			<div id="table1_menu_{item.category.category_id}"></div>
		</td>
	</tr>
</table>
<script type="text/javascript" language="JavaScript">SubCategoryBody_{data.scheme.options.color.value}('table1_menu_{item.category.category_id}', catHref, '{addslashes(item.category,#name#)}', '{data.scheme.options.subcat_icons.value}', '{item.image_url}', '{data.scheme.cat_icons}', '{item.category.category_id}', false);</script>
{else:}
<table border=0 cellpadding=0 cellspacing=0 width="100%">
	<tr>
		<td id="td2_{item.category.category_id}" class="SubMenuOut" onMouseOver="if (!isRealOverAction(event, this)) return; show_menu('{item.category.category_id}', '{data.parent}'); change_color_{data.scheme.options.color.value}('{item.category.category_id}', over);" onMouseOut="if (!isRealOutAction(event, this)) return; change_color_{data.scheme.options.color.value}('{item.category.category_id}', out); close_menu();">
			<div id="table2_menu_{item.category.category_id}"></div>
		</td>
	</tr>
</table>
<script type="text/javascript" language="JavaScript">SubCategoryBody_{data.scheme.options.color.value}('table2_menu_{item.category.category_id}', catHref, '{addslashes(item.category,#name#)}', '{data.scheme.options.subcat_icons.value}', '{item.image_url}', '{data.scheme.cat_icons}', '{item.category.category_id}', true);</script>
{end:}
			</div>
		</div>
	</td>

{else:}	{* // not blue scheme *}

	<td>
		<div FOREACH="data.items,item">
            <script type="text/javascript" language="JavaScript">var catHref='cart.php?target=category&category_id={item.category.category_id}'; var catHref_{item.category.category_id}=catHref;</script>
			<div id="menu_{item.category.category_id}" style="position: relative; cursor: pointer;" onclick="gotoCategory(catHref_{item.category.category_id});">
{if:!item.number}
<table border=0 cellpadding=0 cellspacing=0 width="100%">
	<tr>
		<td {if:item.is_first}{if:item.is_last}class="SubMenuOutFirstLast"{else:}class="SubMenuOutFirst"{end:}{else:}{if:item.is_last}class="SubMenuOutLast"{else:}class="SubMenuOut"{end:}{end:} id="td2_{item.category.category_id}">
			<div id="table1_menu_{item.category.category_id}" onMouseOver="if (!isRealOverAction(event, this)) return; show_menu(0, '{data.parent}'); change_color_{data.scheme.options.color.value}('{item.category.category_id}', over, '{item.is_last}', '{item.is_first}', '0'); change_parents_color_{data.scheme.options.color.value}('{item.category.category_id}', 'over');" onMouseOut="if (!isRealOutAction(event, this)) return; change_color_{data.scheme.options.color.value}('{item.category.category_id}', out, '{item.is_last}', '{item.is_first}', '0'); change_parents_color_{data.scheme.options.color.value}('{item.category.category_id}', 'out'); close_menu();"></div>
		</td>
	</tr>
</table>
<script type="text/javascript" language="JavaScript">SubCategoryBody_{data.scheme.options.color.value}('table1_menu_{item.category.category_id}', catHref, '{addslashes(item.category,#name#)}', '{data.scheme.options.subcat_icons.value}', '{item.image_url}', '{data.scheme.cat_icons}', '{item.category.category_id}', '{item.is_first}', '{item.is_last}', false);</script>

{else:}

<table border=0 cellpadding=0 cellspacing=0 width="100%">
	<tr>
		<td {if:item.is_first}{if:item.is_last}class="SubMenuOutFirstLast"{else:}class="SubMenuOutFirst"{end:}{else:}{if:item.is_last}class="SubMenuOutLast"{else:}class="SubMenuOut"{end:}{end:} id="td2_{item.category.category_id}">
			<div id="table2_menu_{item.category.category_id}" onMouseOver="if (!isRealOverAction(event, this)) return; show_menu('{item.category.category_id}', '{data.parent}'); change_color_{data.scheme.options.color.value}('{item.category.category_id}', over, '{item.is_last}', '{item.is_first}', '1'); change_parents_color_{data.scheme.options.color.value}('{item.category.category_id}', 'over');" onMouseOut="if (!isRealOutAction(event, this)) return; change_color_{data.scheme.options.color.value}('{item.category.category_id}', out, '{item.is_last}', '{item.is_first}', '1'); change_parents_color_{data.scheme.options.color.value}('{item.category.category_id}', 'out'); close_menu();"></div>
		</td>
	</tr>
</table>
<script type="text/javascript" language="JavaScript">SubCategoryBody_{data.scheme.options.color.value}('table2_menu_{item.category.category_id}', catHref, '{addslashes(item.category,#name#)}', '{data.scheme.options.subcat_icons.value}', '{item.image_url}', '{data.scheme.cat_icons}', '{item.category.category_id}', '{item.is_first}', '{item.is_last}', true);</script>
{end:}
			</div>
		</div>
	</td>
{end:}
</tr>
</table>
</div>
