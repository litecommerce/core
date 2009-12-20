{if:config.FlyoutCategories.flyout_categories_built&config.FlyoutCategories.scheme}
<noscript>
<widget class="CTopCategories" template="common/sidebar_box_cat.tpl" head="Categories" dir="categories">
</noscript>
<widget module="FlyoutCategories" template="modules/FlyoutCategories/catalog{xlite.auth.flyoutCategoriesCacheDirectory}/categories.tpl" layout="side">
{else:}
<widget class="CTopCategories" template="common/sidebar_box_cat.tpl" head="Categories" dir="categories">
{end:}
