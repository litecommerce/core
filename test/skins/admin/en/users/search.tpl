<widget template="common/dialog.tpl" head="User search" body="users/search_form.tpl">

<span IF="mode=#search#">{count} account(s) found
<br>
<widget template="common/dialog.tpl" head="Search results" body="users/search_results.tpl" visible="{count}">
</span>
