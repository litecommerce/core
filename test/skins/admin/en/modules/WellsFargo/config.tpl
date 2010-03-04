Use this page to configure your store to communicate with your Payment Processing Gateway. Complete the required fields below and press the "Update" button.<hr>

<p>
<span class="SuccessMessage" IF="updated">Wells Fargo parameters were successfully changed. Please make sure that the Wells Fargo payment method is enabled on the <a href="admin.php?target=payment_methods"><u>Payment methods</u></a> page before you can start using it.</span>
<p>
<B>Note:</B> In setup Wells Fargo payment gateway, you have to proceed these steps:
<LI>Log in to your Wells Fargo backoffice</LI>
<LI>Go to the '<I>Manage Store/Order Rules/Configure Options</I>' menu</LI>
<LI>Go to the 'When a Shopper Places an Order' section</LI>
<LI> In the '<I>Approvals to:</I>'/'<I>Rejections to:</I>' set callback URL's to:<br>{shopURL(#cart.php?target=callback&action=callback&order_id_name=IOC_merchant_order_id
#)}</LI>
<p>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{pm.get(#payment_method#)}">
<table border=0 cellspacing=10>

<tr>
<td>Store ID:</td>
<td><input type=text name=params[param01] size=32 value="{pm.params.param01}"></td>
</tr>

<tr>
<td>Order prefix:</td>
<td><input type=text name=params[param03] size=32 value="{pm.params.param03}"></td>
</tr>

<tr>
<td>Wells Fargo gateway URL:</td>
<td><input type=text name=params[param08] size=32 value="{pm.params.param08}"></td>
</tr>

<tr>
<td colspan="2">
<input type=submit value=" Update ">
</td>
</tr>

</table>
</form>
