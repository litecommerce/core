{* Icons category menu body *}

<script type="text/javascript" language="JavaScript" src="{data.skinPath}/modules/FlyoutCategories/layerslibvar.js"></script>
<script type="text/javascript" language="JavaScript" src="{data.skinPath}/modules/FlyoutCategories/layerslib.js"></script>
<script type="text/javascript" language="JavaScript" src="{data.catalogPath}/script.js"></script>

<script type="text/javascript" language="JavaScript">
var dropDir = {if:data.scheme.options.drop_direction.value}'{data.scheme.options.drop_direction.value}'{else:}'right'{end:};
var dropLogic = {if:data.scheme.options.drop_logic.value}'{data.scheme.options.drop_logic.value}'{else:}'smart'{end:};
var dropDirDefault = dropDir;
var static_catalog_path = "";
var static_catalog_name = "";
var img_path = "{data.imagesPath}{data.scheme.options.color.value}";
var menuPopupDelay = "{data.scheme.options.popup_delay.value}";
var menuVisibleDelay = "{data.scheme.options.close_delay.value}";
</script>

<script type="text/javascript" language="JavaScript" src="{data.skinPath}/modules/FlyoutCategories/menumanagement.js"></script>

<script type="text/javascript" language="JavaScript">
InitPopUp();
is_icons = true;
</script>

<script type="text/javascript" language="JavaScript" src="{data.catalogPath}/body_header.js"></script>

<script type="text/javascript" language="JavaScript">
{foreach:data.items,item}
parents[{item.category.category_id}] = Array();
{foreach:item.path_ids,itemIndex,v}
parents[{item.category.category_id}][{itemIndex}] = {v};
{end:}
last_data[{item.category.category_id}] = '{item.is_last}';
first_data[{item.category.category_id}] = '{item.is_first}';
previous_data[{item.category.category_id}] = '{item.previous}';
next_data[{item.category.category_id}] = '{item.next}';
depth_data[{item.category.category_id}] = '{item.depth}';
{end:}
</script>

<div id="rootmenu">
<table border=0 cellpadding=0 cellspacing=0 width="100%">
    <tr>
    	<td class="BorderOut" id="first"><img src="{data.imagesPath}{data.scheme.options.color.value}/spacer.gif" width=1 height=1 alt=""></td>
	</tr>
	<tbody FOREACH="data.items,item">
        <tr>
			<td>
				<script type="text/javascript" language="JavaScript">var catHref='cart.php?target=category&category_id={item.category.category_id}'; var catHref_{item.category.category_id}=catHref;</script>
{if:item.number}
				<div id="cat_{item.category.category_id}" style="position: relative; left: 0; top: 0" onmouseOver="if (!isRealOverAction(event, this)) return; show_menu('{item.category.category_id}', 0); change_color('{item.category.category_id}', 'over');" OnMouseOut="if (!isRealOutAction(event, this)) return; change_color('{item.category.category_id}', 'out'); close_menu();" onclick="gotoCategory(catHref_{item.category.category_id});" class="CatOut"></div>
				<script type="text/javascript" language="JavaScript">CategoryBodyA('cat_{item.category.category_id}', catHref, '{addslashes(item.category,#name#)}', '{data.scheme.subcat_icons}', '{item.image_url}', '{data.scheme.rootcat_icons}', '{data.scheme.options.rootcat_icons_width.value}');</script>
{else:}
				<div id="cat_{item.category.category_id}" style="position: relative; left: 0; top: 0;" class="CatOut" onmouseOver="if (!isRealOverAction(event, this)) return; change_color('{item.category.category_id}', 'over');" OnMouseOut="if (!isRealOutAction(event, this)) return; change_color('{item.category.category_id}', 'out');" onclick="gotoCategory(catHref_{item.category.category_id});"></div>
				<script type="text/javascript" language="JavaScript">CategoryBodyB('cat_{item.category.category_id}', catHref, '{addslashes(item.category,#name#)}', '{data.scheme.subcat_icons}', '{item.image_url}', '{data.scheme.rootcat_icons}', '{data.scheme.options.rootcat_icons_width.value}');</script>
{end:}
			</td>
        </tr>
	</tbody>
</table>
</div>
<div><img src="{data.imagesPath}{data.scheme.options.color.value}/cat_line_01.gif" alt="" height="4" width="173"></div>
