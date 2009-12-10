{* Candy Category menu body *}
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
is_candy = true;
</script>

<script type="text/javascript" language="JavaScript" src="{data.catalogPath}/body_header.js"></script>

<script type="text/javascript" language="JavaScript">
{foreach:data.items,item}
parents[{item.category.category_id}] = Array();
{foreach:item.path_ids,itemIndex,v}
parents[{item.category.category_id}][{itemIndex}] = {v};
{end:}
{end:}
</script>
<div class="CatContainer">
<div id="rootmenu">
<table border=0 cellpadding=0 cellspacing=0 width="100%">
    <tr class="IndentTR">
        <td class="IndentTD"><img src="{data.imagesPath}{data.scheme.options.color.value}/spacer.gif" class="IndentIMG" alt="" /></td>
    </tr>
	<tbody FOREACH="data.items,item">
        <tr>
			<td onclick="gotoCategory(catHref_{item.category.category_id});" style="cursor: pointer;" class="CatTD">
            <script type="text/javascript" language="JavaScript">var catHref='cart.php?target=category&category_id={item.category.category_id}'; var catHref_{item.category.category_id}=catHref;</script>
{if:item.number}
            <div id="cat_{item.category.category_id}" style="position: relative; left: 0; top: 0;" onmouseOver="if (!isRealOverAction(event, this)) return; show_menu('{item.category.category_id}', 0); change_color_{data.scheme.options.color.value}('{item.category.category_id}', 'over', 'cat_');" OnMouseOut="if (!isRealOutAction(event, this)) return; change_color_{data.scheme.options.color.value}('{item.category.category_id}', 'out', 'cat_'); close_menu();"></div>
<script type="text/javascript" language="JavaScript">CategoryBody_{data.scheme.options.color.value}('cat_{item.category.category_id}', catHref, '{addslashes(item.category,#name#)}');</script>
{else:}
			<div id="cat_{item.category.category_id}" style="position: relative; left: 0; top: 0;" onmouseOver="if (!isRealOverAction(event, this)) return; show_menu('{item.category.category_id}', 0); change_color_{data.scheme.options.color.value}('{item.category.category_id}','over', 'cat_');" OnMouseOut="if (!isRealOutAction(event, this)) return; change_color_{data.scheme.options.color.value}('{item.category.category_id}', 'out', 'cat_'); close_menu();" class="CatOut"></div>
<script type="text/javascript" language="JavaScript">CategoryBody_{data.scheme.options.color.value}('cat_{item.category.category_id}', catHref, '{addslashes(item.category,#name#)}');</script>
{end:}
			</td>
        </tr>
		<tr class="IndentTR">
			<td class="IndentTD"><img src="{data.imagesPath}{data.scheme.options.color.value}/spacer.gif" class="IndentIMG" alt="" /></td>
		</tr>
	</tbody>
</table>
</div>
</div>
<div><img src="{data.imagesPath}{data.scheme.options.color.value}/cat_line_01.gif" alt="" height="4" width="173"></div>
