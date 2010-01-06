{* Horizontal category menu body *}
<script type="text/javascript" language="JavaScript" src="{data.catalogPath}/script.js"></script>
<script IF="data.scheme.options.color.value=#FashionBoutique#" type="text/javascript" language="JavaScript" src="{data.catalogPath}/script/FashionBoutique.js"></script>
<script IF="data.scheme.options.color.value=#GiftsShop#" type="text/javascript" language="JavaScript" src="{data.catalogPath}/script/GiftsShop.js"></script>
<script IF="data.scheme.options.color.value=#SummerSports#" type="text/javascript" language="JavaScript" src="{data.catalogPath}/script/SummerSports.js"></script>
<script IF="data.scheme.options.color.value=#WinterSports#" type="text/javascript" language="JavaScript" src="{data.catalogPath}/script/WinterSports.js"></script>
<script type="text/javascript" language="JavaScript">
var dropDir = {if:data.scheme.options.drop_direction.value}'{data.scheme.options.drop_direction.value}'{else:}'right'{end:};
var dropLogic = {if:data.scheme.options.drop_logic.value}'{data.scheme.options.drop_logic.value}'{else:}'smart'{end:};
var dropDirDefault = dropDir;
var static_catalog_path = "";
var static_catalog_name = "";
var img_path = "{data.imagesPath}{data.scheme.options.color.value}";
var color = "{data.scheme.options.color.value}";
var arrow_visible = "{data.scheme.options.subcat_icons.value}";
var menuPopupDelay = "{data.scheme.options.popup_delay.value}";
var menuVisibleDelay = "{data.scheme.options.close_delay.value}";

init_{data.scheme.options.color.value}();
InitMenuImages_{data.scheme.options.color.value}();
</script>

<script type="text/javascript" language="JavaScript" src="{data.skinPath}/modules/FlyoutCategories/layerslibvar.js"></script>
<script type="text/javascript" language="JavaScript" src="{data.skinPath}/modules/FlyoutCategories/layerslib.js"></script>
<script type="text/javascript" language="JavaScript" src="{data.skinPath}/modules/FlyoutCategories/menumanagement.js"></script>
<script type="text/javascript" language="JavaScript">
InitPopUp();
is_flat = true;
</script>

<script type="text/javascript" language="JavaScript" src="{data.catalogPath}/body_header.js"></script>

{if:!data.scheme.options.color.value=#blue#}
<script type="text/javascript" language="JavaScript">
{foreach:data.items,item}
previous[{item.category.category_id}] = '{item.previous}';
next[{item.category.category_id}] = '{item.next}';
first['cat_{item.category.category_id}'] = '{item.is_first}';

num = num + 1;
img_ids[{item.category.category_id}] = num;
{end:}
</script>
{end:}

<div id="rootmenu">
<table width="100%" border=0 cellpadding=0 cellspacing=0>
<tr>

{if:data.scheme.options.color.value=#blue#}
   	<td height=26  class="FlyoutContainer" style="background-image: url('{data.imagesPath}{data.scheme.options.color.value}/bg.gif');">
        <table border=0 cellpadding=0 cellspacing=0>
	        <tr>
				<td width=2><img src="{data.imagesPath}blue/but.gif" width=2 height=26 alt="" /></td>
				<td FOREACH="data.items,item">

<table cellspacing=0 cellpadding=0>
	<tr>
		<td height=26 class="RootMenuOut" id="td_{item.category.category_id}">
<script type="text/javascript" language="JavaScript">var catHref='cart.php?target=category&category_id={item.category.category_id}'; var catHref_{item.category.category_id}=catHref;</script>
{if:item.number}
            <div id="cat_{item.category.category_id}" style="position: relative; left: 0; top: 0;" onMouseOver="if (!isRealOverAction(event, this)) return; show_menu('{item.category.category_id}', 0); change_color_root_{data.scheme.options.color.value}('{item.category.category_id}', 'over');" onMouseOut="if (!isRealOutAction(event, this)) return; change_color_root_{data.scheme.options.color.value}('{item.category.category_id}', 'out'); close_menu();" onclick="gotoCategory(catHref_{item.category.category_id});"></div>
<script type="text/javascript" language="JavaScript">CategoryBody_{data.scheme.options.color.value}('cat_{item.category.category_id}', catHref, '{addslashes(item.category,#name#)}', '{item.category.category_id}');</script>
{else:}
			<div id="cat_{item.category.category_id}" style="position: relative; left: 0; top: 0;" onMouseOver="if (!isRealOverAction(event, this)) return; change_color_root_{data.scheme.options.color.value}('{item.category.category_id}', 'over');" onMouseOut="if (!isRealOutAction(event, this)) return; change_color_root_{data.scheme.options.color.value}('{item.category.category_id}', 'out');" onclick="gotoCategory('{item.category.category_id}');"></div>
<script type="text/javascript" language="JavaScript">CategoryBody_{data.scheme.options.color.value}('cat_{item.category.category_id}', catHref, '{addslashes(item.category,#name#)}', '{item.category.category_id}');</script>
{end:}
		</td>
	</tr>
</table>

        		</td>
    	    </tr>
        </table>
	</td>

{else:}

   	<td class="FlyoutContainer" style="background-image: url('{data.imagesPath}{data.scheme.options.color.value}/bg.gif');">
        <table border=0 cellpadding=0 cellspacing=0>
        <tr>
            <td FOREACH="data.items,item" id="td_{item.category.category_id}" class="RootMenuOut">
<script type="text/javascript" language="JavaScript">var catHref='cart.php?target=category&category_id={item.category.category_id}'; var catHref_{item.category.category_id}=catHref;</script>
{if:item.number}
			<div id="cat_{item.category.category_id}" style="position: relative; left: 0; top: 0;"  onMouseOver="if (!isRealOverAction(event, this)) return; show_menu('{item.category.category_id}', 0); change_color_root_{data.scheme.options.color.value}('{item.category.category_id}', 'over');" onMouseOut="if (!isRealOutAction(event, this)) return; change_color_root_{data.scheme.options.color.value}('{item.category.category_id}', 'out'); close_menu();" onclick="gotoCategory(catHref_{item.category.category_id});"></div>
            <script type="text/javascript" language="JavaScript">CategoryBody_{data.scheme.options.color.value}('cat_{item.category.category_id}', catHref, '{addslashes(item.category,#name#)}', '{item.category.category_id}', '{item.is_first}');</script>
{else:}
			<div id="cat_{item.category.category_id}" style="position: relative; left: 0; top: 0;" onMouseOver="if (!isRealOverAction(event, this)) return; change_color_root_{data.scheme.options.color.value}('{item.category.category_id}', 'over');" onMouseOut="if (!isRealOutAction(event, this)) return; change_color_root_{data.scheme.options.color.value}('{item.category.category_id}', 'out');" onclick="gotoCategory(catHref_{item.category.category_id});"></div>
            <script type="text/javascript" language="JavaScript">CategoryBody_{data.scheme.options.color.value}('cat_{item.category.category_id}', catHref, '{addslashes(item.category,#name#)}', '{item.category.category_id}', '{item.is_first}');</script>
{end:}
			</td>
        </tr>
        </table>
	</td>
{end:}

</tr>
</table>
</div>
