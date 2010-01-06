<widget template="modules/Affiliate/dialog.tpl" head="Search product" body="modules/Affiliate/product_search.tpl">

<span class="Text" IF="mode=#search#">{productsFound} product(s) found</span>

<widget template="modules/Affiliate/dialog.tpl" head="Search results" body="modules/Affiliate/product_list.tpl" visible="{mode=#search#&products}">

