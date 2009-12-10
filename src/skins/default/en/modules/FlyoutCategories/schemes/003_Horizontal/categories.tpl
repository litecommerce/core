{if:widget.layout=#flat#}
<widget template="modules/FlyoutCategories/catalog{xlite.auth.flyoutCategoriesCacheDirectory}/body.tpl">

<script language="JavaScript1.2" type="text/javascript">
{foreach:category.path,v}
	id = "{v.category_id}";
	if(document.getElementById('td_' + id))	{
		change_color_root(id, 'over', "selected");
		sel_root_cat = id;
	}
{end:}
</script>
{end:}
