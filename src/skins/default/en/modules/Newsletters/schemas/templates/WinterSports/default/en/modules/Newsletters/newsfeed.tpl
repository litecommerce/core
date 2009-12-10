<div IF="xlite.factory.NewsList.showedListsNumber">

<widget class="CBulletin">

<form action="{shopURL(#cart.php#)}" method=POST name="newsfeed_form">
<input type=hidden name=target value=news>
<input type=hidden name=action value=subscribe>

<table border=0 cellpadding=1 cellspacing=1>

<tr>
    <td> Your e-mail:<br><input type=text size=18 name=email value="{email}" style="width: 130px;"> </td>
</tr>
<tr>
    <td><widget template="common/button_log.tpl" label="Subscribe" href="javascript: document.newsfeed_form.submit()" font="FormButton"></td>
</tr>

</table>
</form>

</div>

<span IF="!xlite.factory.NewsList.showedListsNumber">
There are currently no news available.
</span>
