{if:!mode=#confirmation#}

<widget template="common/dialog.tpl" head="Search product" body="product/search.tpl">

<span class="Text" IF="mode=#search#">
	<span IF="!productsFound">No products found.</span>
	<span IF="productsFound">{productsFound} product<span IF="!productsFound=#1#">s</span> found.</span>
</span>

<widget template="common/dialog.tpl" head="Search results" body="product/product_list.tpl" visible="{mode=#search#&products}" />

{else:}

<widget template="common/dialog.tpl" head="Confirmation" body="product/products_delete.tpl" />

{end:}
