{if:widget.layout=#side#}

<widget template="common/sidebar_box_cat.tpl" dir="modules/FlyoutCategories/catalog{xlite.auth.flyoutCategoriesCacheDirectory}" head="Categories">
<script language="JavaScript1.2" type="text/javascript">
{foreach:category.path,v}
	id = "{v.category_id}";
	if(document.getElementById('cat_' + id))	{
		change_color(id, 'over', 'cat_', 'selected');
		sel_root_cat = id;
	}
{end:}
</script>

{end:}
