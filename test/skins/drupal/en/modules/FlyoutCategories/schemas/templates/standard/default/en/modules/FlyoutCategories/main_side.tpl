{if:config.FlyoutCategories.flyout_categories_built&config.FlyoutCategories.scheme}
<noscript>
<widget class="XLite_View_TopCategories" template="common/sidebar_box.tpl" head="Categories" dir="categories">
</noscript>
<widget module="FlyoutCategories" template="modules/FlyoutCategories/catalog{xlite.auth.flyoutCategoriesCacheDirectory}/categories.tpl" layout="side">
{else:}
<widget class="XLite_View_TopCategories" template="common/sidebar_box.tpl" head="Categories" dir="categories">
{end:}
