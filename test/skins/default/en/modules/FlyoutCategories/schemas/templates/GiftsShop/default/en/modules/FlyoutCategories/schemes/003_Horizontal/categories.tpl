{if:layout=#flat#}
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
{else:}
    <div style="padding: 0px; margin: 0px;"><img src="images/custom/modules/FlyoutCategories/categories.gif" width="173" height="76" alt=""></div>
{end:}
