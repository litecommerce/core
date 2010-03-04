{if:layout=#side#}
<widget template="common/sidebar_box_cat.tpl" dir="modules/FlyoutCategories/catalog{xlite.auth.flyoutCategoriesCacheDirectory}" head="Categories">

<script language="JavaScript" type="text/javascript"> {foreach:category.path,v} setTimeout("displayObjectSelected('{v.category_id}', true)", 1);{end:} setFirstImg(); </script>
{end:}
