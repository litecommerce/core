{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<form action="admin.php" method="POST" IF="specialOffers" name="offers_form">
<input type="hidden" name="target" value="SpecialOffers">
<input type="hidden" name="action" value="update">
<table border="0" IF="specialOffers" cellpadding=0 cellspacing=0>
<tr><td class=CenterBorder>

<table border="0" cellpadding=5 cellspacing=1>
<tr class=Center>
<th></th>
<th valign="center">
Creation date
<a href="{getShopUrl(#admin.php?target=SpecialOffers&sort=date_desc#)}"><img border="0" src="images/modules/Promotion/up_arrow.gif"></a>
<a href="{getShopUrl(#admin.php?target=SpecialOffers&sort=date_asc#)}"><img border="0" src="images/modules/Promotion/down_arrow.gif"></a>
</th>
<th valign="center">
Title
<a href="{getShopUrl(#admin.php?target=SpecialOffers&sort=title_asc#)}"><img border="0" src="images/modules/Promotion/up_arrow.gif"></a>
<a href="{getShopUrl(#admin.php?target=SpecialOffers&sort=title_desc#)}"><img border="0" src="images/modules/Promotion/down_arrow.gif"></a>
</th>
<th valign="center">
Active
<a href="{getShopUrl(#admin.php?target=SpecialOffers&sort=active_asc#)}"><img border="0" src="images/modules/Promotion/up_arrow.gif"></a>
<a href="{getShopUrl(#admin.php?target=SpecialOffers&sort=active_desc#)}"><img border="0" src="images/modules/Promotion/down_arrow.gif"></a>
</th>
<th valign="center">Status</th>
<th valign="center">
Start date
<a href="{getShopUrl(#admin.php?target=SpecialOffers&sort=s_date_asc#)}"><img border="0" src="images/modules/Promotion/up_arrow.gif"></a>
<a href="{getShopUrl(#admin.php?target=SpecialOffers&sort=s_date_desc#)}"><img border="0" src="images/modules/Promotion/down_arrow.gif"></a>
</th>
<th valign="center">
End date
<a href="{getShopUrl(#admin.php?target=SpecialOffers&sort=e_date_asc#)}"><img border="0" src="images/modules/Promotion/up_arrow.gif"></a>
<a href="{getShopUrl(#admin.php?target=SpecialOffers&sort=e_date_desc#)}"><img border="0" src="images/modules/Promotion/down_arrow.gif"></a>
</th>
<th>&nbsp;</th></tr>
<tr class=Center FOREACH="specialOffers,specialOffer">
	<td><input type="checkbox" name="offer_ids[{specialOffer.offer_id}]"></td>
	<td>{formatDate(specialOffer,#date#)}</td>
	<td>{specialOffer.title}</td>
	<td align="center"><input type="checkbox" name="active[{specialOffer.offer_id}]" checked="{specialOffer.enabled}"></td>
	<td>{if:specialOffer.status=#Available#}
		<font color=green>
		{end:}
		{if:specialOffer.status=#Expired#}
        <font color=black>
        {end:}
		{if:specialOffer.status=#Upcoming#}
		<font color=darkgreen>
		{end:}
		{if:specialOffer.status=#Invalid#}
		<font color=red>
		{end:}
		{specialOffer.status}
		</font>
	</td>
	<td>{formatDate(specialOffer,#start_date#)}</td>
	<td>{formatDate(specialOffer,#end_date#)}</td>
	<td><a href="admin.php?target=SpecialOffer&offer_id={specialOffer.offer_id}"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Edit</a></td>
</tr>
</table>
 
</td>
</tr>
</table>
<br>
<script language="javascript">
	function confirm_delete()
	{
		if(confirm('Are you sure ?')) { 
			document.offers_form.action.value='delete'; 
			document.offers_form.submit(); 
		}
	}
</script>
<input type=button value="Delete" onClick="javascript: confirm_delete();">&nbsp;
<input type=button value="Clone" onClick="javascript: document.offers_form.action.value='clone'; document.offers_form.submit();">&nbsp;
<input type="submit" value="Update">

</form>
<a href="admin.php?target=SpecialOffer&offer_id="><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> <b>Add new special offer</b></a>
