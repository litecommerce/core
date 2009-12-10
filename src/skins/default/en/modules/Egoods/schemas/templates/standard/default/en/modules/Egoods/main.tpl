<widget target="download" mode="file_access_denied" template="common/dialog.tpl" head="Access denied" body="modules/Egoods/file_access_denied.tpl">
<widget target="download" mode="file_not_found" template="common/dialog.tpl" head="File not found" body="modules/Egoods/file_not_found.tpl">
<widget target="product" template="common/dialog.tpl" head="Download this product for free" body="modules/Egoods/free_downloads.tpl" IF="product.isFreeForMembership(cart.profile.membership)"/>
