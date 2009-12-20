<widget template="common/dialog.tpl" body="modules/WishList/search.tpl" head="Search wish lists">
<span class="Text" IF="mode=#search#&count">
Found {count} wishlist{if:count=#1#}{else:}s{end:}<br>		 
<widget template="common/dialog.tpl" mode="search" body="modules/WishList/list.tpl" head="Search results">
</span>
<span class="Text" IF="!count">No wish lists found</span>


