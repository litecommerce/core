{* Explorer category menu body *}

<script type="text/javascript" language="JavaScript">
var static_catalog_path = "";
var static_catalog_name = "";
var img_path = "{data.imagesPath}{data.scheme.options.color.value}";
</script>

<script type="text/javascript" language="JavaScript" src="{data.catalogPath}/script.js"></script>
<script type="text/javascript" language="JavaScript" src="{data.skinPath}/modules/FlyoutCategories/layerslibvar.js"></script>
<script type="text/javascript" language="JavaScript" src="{data.skinPath}/modules/FlyoutCategories/layerslib.js"></script>
<script type="text/javascript" language="JavaScript" src="{data.skinPath}/modules/FlyoutCategories/menumanagement.js"></script>

<script type="text/javascript" language="JavaScript" src="{data.catalogPath}/body_header.js"></script>

<table border=0 cellspacing=0 cellpadding=0 width="100%">
<tr>
	<td colspan=2 height=5></td>
</tr>
</table>
<table border=0 cellspacing=0 cellpadding=0>
<tr>
	<td><a href="javascript: void(0);" OnClick="displayAll(true);"><img src="{data.catalogPath}/images/2icon_plus.gif" border="0" onmouseover="javascript: this.src='{data.catalogPath}/images/2icon_active_plus.gif';"  onmouseout="javascript: this.src='{data.catalogPath}/images/2icon_plus.gif';" alt="Expand all"></a></td>
	<td width=2><img src="{data.imagesPath}{data.scheme.options.color.value}/spacer.gif" width=2 height=1 alt=""></td>
	<td><a href="javascript: void(0);" OnClick="displayAll(false);"><img src="{data.catalogPath}/images/1icon.gif" border=0 onmouseover="javascript: this.src='{data.catalogPath}/images/1icon_active.gif';"   onmouseout="javascript: this.src='{data.catalogPath}/images/1icon.gif';" alt="Collapse all"></a></td>
</tr>
</table>
<table border=0 cellspacing=0 cellpadding=0 width="100%">
<tr>
	<td colspan=2><hr></td>
</tr>
</table>

<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td>

{foreach:data.nodes,node}

{if:node.tag_type=#close#}
{foreach:node.tags,v}{v:r}{end:}
{end:}

{if:node.is_first=#1#}<div id="parent_{node.parent}" {if:node.parent}style="display: none;"{end:}>{end:}

<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td FOREACH="node.chain,k,v" width=20 height=20  valign=top class="FlyoutBG" align=left onClick="this.blur();" id="td_{node.category_id}_{k}">{if:v=#B#}{if:node.is_leaf}<img src="{data.imagesPath}{data.scheme.options.color.value}/beg3.gif" border=0 width=20 height=20 alt="">{else:}<a href="javascript: void(0);" OnClick="switchCategory({node.category_id});"><img src="{data.imagesPath}{data.scheme.options.color.value}/beg2.gif" border=0 width=20 height=20 id="img_{node.category_id}" alt=""></a>{end:}{end:}{if:v=#M#}<img src="{data.imagesPath}{data.scheme.options.color.value}/mid.gif" border=0 width=20 height=20 alt="">{end:}{if:v=#E#}<script type="text/javascript" language="JavaScript"> last_list[{node.category_id}] = "last"; </script>{if:node.subcategories_chunks}<a href="javascript: void(0);" OnClick="switchCategory({node.category_id});"><img src="{data.imagesPath}{data.scheme.options.color.value}/beg_end.gif" border=0 width=20 height=20 id="img_{node.category_id}" alt=""></a>{else:}<img src="{data.imagesPath}{data.scheme.options.color.value}/end.gif" border=0 width=20 height=20 alt="">{end:}{end:}{if:v=#1#}<img src="{data.imagesPath}{data.scheme.options.color.value}/neopen.gif" id="img_sel_{node.category_id}" border=0 width=20 height=20 alt="">{end:}{if:v=#2#}<script type="text/javascript" language="JavaScript"> nodes_list[index++] = {node.category_id}; imgs[{node.category_id}] = 'td_{node.category_id}_{k}'; </script><a href="javascript: void(0);" OnClick="switchCategory({node.category_id});"><img src="{data.imagesPath}{data.scheme.options.color.value}/nclose.gif" id="node_{node.category_id}" border=0 width=20 height=20 alt=""></a>{end:}{if:v=##}<img src="{data.imagesPath}{data.scheme.options.color.value}/spacer.gif" width=20 height=20 alt="">{end:}</td>
<td><script type="text/javascript" language="JavaScript">var catHref='cart.php?target=category&category_id={node.category_id}'; CategoryBodyA('{node.category_id}', catHref, '{addslashes(node.name)}');</script></td>
</tr>
</table>
<script FOREACH="node.chain,k,v" type="text/javascript" language="JavaScript"> {if:v=#B#} setBGImage(document.getElementById('td_{node.category_id}_{k}'), imgMid2.src);{end:} {if:v=#M#} setBGImage(document.getElementById('td_{node.category_id}_{k}'), imgMid2.src);{end:} </script>
{end:}
</div>
</td>
</tr>
</table>
