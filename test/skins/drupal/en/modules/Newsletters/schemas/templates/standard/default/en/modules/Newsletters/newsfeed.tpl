<div IF="xlite.factory.XLite_Module_Newsletters_Model_NewsList.showedListsNumber">

<widget class="XLite_Module_Newsletters_View_Bulletin" >

<form action="{shopURL(#cart.php#)}" method=POST>
<input type=hidden name=target value=news>
<input type=hidden name=action value=subscribe>

<table border=0 cellpadding=1 cellspacing=1>

<tr>
    <td> Your e-mail:<br><input type=text size=20 name=email value="{email}"> </td>
</tr>
<tr>
    <td> <input type=submit name=subscribe value=Subscribe> </td>
</tr>

</table>
</form>

</div>

<span IF="!xlite.factory.XLite_Module_Newsletters_Model_NewsList.showedListsNumber">
There are currently no news items available.
</span>
