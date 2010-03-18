{* Subscribe to newsletters page *}
<p align=justify>Use the form below to subscribe to our electronic newsletters.</p>

<br>

<form name="subscribe_form" action="{getShopUrl(#cart.php#)}" method=POST>
<input FOREACH="allparams,_p,_v" type=hidden name="{_p}" value="{_v}"/>
<input type=hidden name=action value=subscribe>

<table border="0" cellspacing="0" cellpadding="2">
<tr>
    <td>E-mail:&nbsp;</td>
    <td class=Star>*</td>
    <td>
        <input type="text" name="email" value="{email:r}" size="30" maxlength="128">
        <widget class="XLite_Validator_EmailValidator" field="email">
    </td>
</tr>
<tr>
    <td colspan=2>&nbsp;</td>
    <td><widget class="XLite_View_Submit" href="javascript: document.subscribe_form.submit()"></td>
</tr>
<tr>
    <td colspan=2>&nbsp;</td>
    <td><br><a href="cart.php?target=help&amp;mode=privacy_statement"><u>Privacy statement</u></a></td>

</tr>
</table>

</form>

