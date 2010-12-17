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
<form name="checkout" action="cart.php" method="POST">
<input type="hidden" name="target" value="checkout">
<input type="hidden" name="action" value="checkout">

<table width="100%" border="0" border="0" cellspacing="0" cellpadding="2">
<tr valign="middle">
	<td height="20" colspan="4"><b>Please enter the number of bonus points</b><hr size=1 noshade></td>
</tr>
<tr valign="middle">
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td><b>Bonus points</b></td>
	<td><b>Currency</b></td>
</tr>
<tr valign="middle">
	<td align="right" width="40%">Order total:</td>
	<td width="10">&nbsp;</td>
	<td width="20%">{cart.totalBonusPoints}</td>
	<td>({price_format(cart.totalWithBonusPoints):h})</td>
</tr>
<tr valign="middle">
	<td align="right" width="40%">Bonus points available:</td>
	<td width="10">&nbsp;</td>
	<td>{cart.origProfile.bonusPoints}</td>
	<td>({price_format(cart.origProfile.bonusPointsDollar):h})</td>
</tr>

<tr valign="middle">
	<td align="right" width="40%">Pay by bonus points:</td>
	<td width="10"><font class="Star">*</font></td>
	<td><input type="text" name="payedByPoints" value="{cart.payByPoints}" size="6" onChange="bonusPointsChanged()"></td>
	<td>(<span ID="bonusPointsDollar"></span>) <input type="button" value="recalculate" onClick="bonusPointsChanged()"></td>
</tr>
</table>
<span class="ErrorMEssage" ID="bonusPointsWarning"></span>
<input type="hidden" name="priceFormat" value="{config.General.price_format:r}">
<br>
<widget class="\XLite\View\Button" label="Submit order" href="javascript: bonusPointsFormSubmit();">
</form>

<script>
var bonusPointsCost = {config.CDev.Promotion.bonusPointsCost};
var maxValue = {cart.getTotalBonusPoints()};
var maxAvailable = {cart.origProfile.bonusPoints};
// <!--
function bonusPointsFormSubmit()
{
    if (bonusPointsChanged()) {
        document.checkout.submit();
    }
}

function bonusPointsChanged()
{
	var priceFormat = document.checkout.priceFormat.value;
	var payedByPoints = document.checkout.payedByPoints.value;
	payedByPoints = payedByPoints.replace(/^ +/, "")
	payedByPoints = payedByPoints.replace(/ +$/, "")
	warning = ""
	if (payedByPoints.match(/^[0-9]+$/)) {
		if (payedByPoints>maxValue) {
			warning = "Too many bonus points for this order"
		}
		if (payedByPoints>maxAvailable) {
			warning = "You do not have enough bonus points"
		}
		
		document.getElementById("bonusPointsDollar").innerHTML = priceFormat.replace(/%s/,round(payedByPoints*bonusPointsCost))
	} else {
		warning = "Wrong number format"
	}
	document.getElementById("bonusPointsWarning").innerHTML = warning
	return warning == ""
}
function round(n)
{
	return String(n*100).replace(/\..*$/, "") / 100;
}
bonusPointsChanged()
// -->
</script>
