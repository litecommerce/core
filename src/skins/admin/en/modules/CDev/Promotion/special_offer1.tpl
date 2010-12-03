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
<form action="admin.php" method="POST" name="offerForm">
<input type="hidden" name="target" value="SpecialOffer">
<input type="hidden" name="action" value="update1">
<input type="hidden" name="offer_id" value="{offer_id}">
<h2>1. Describe condition when this special offer applies:</h2>
<blockquote>
<input type="radio" name="conditionType" value="productAmount" checked="{isSelected(conditionType,#productAmount#)}" onclick="eachNthChange(0)"> Customer buys a certain quantity of a product <br>
<input type="radio" name="conditionType" value="orderTotal" checked="{isSelected(conditionType,#orderTotal#)}" onclick="eachNthChange(0)"> Order subtotal exceeds a certain amount <br>
<input type="radio" name="conditionType" value="productSet" checked="{isSelected(conditionType,#productSet#)}" onclick="eachNthChange(0)"> Customer buys a specified set of products <br>
<input type="radio" name="conditionType" value="bonusPoints" checked="{isSelected(conditionType,#bonusPoints#)}" onclick="eachNthChange(0)"> Customer earns a certain number of bonus points <br>
<input id="eachNth" type="radio" name="conditionType" value="eachNth" checked="{isSelected(conditionType,#eachNth#)}" onclick="eachNthChange(1)"> Every Nth product purchased <br>
<span IF="hasMemberships()">
<input type="radio" name="conditionType" value="hasMembership" checked="{isSelected(conditionType,#hasMembership#)}" onclick="eachNthChange(0)"> Customer has a certain membership
</span>
</blockquote>

<script>
// <!--
function eachNthChange(disable)
{
	document.getElementById("freeShipping").disabled = disable
	document.getElementById("bonusPoints").disabled = disable
	document.getElementById("discounts").disabled = disable
}
// -->
</script>
<h2>2. Specify the type of bonus the customer gets when his order meets the condition:</h2>
<blockquote>
<input id="discounts" type="radio" name="bonusType"  value="discounts" checked="{isSelected(bonusType,#discounts#)}"> Discount on a category and/or products <br>
<input type="radio" name="bonusType"  value="specialPrices" checked="{isSelected(bonusType,#specialPrices#)}"> Specially-priced/free product <br>
<input id="freeShipping" type="radio" name="bonusType"  value="freeShipping" checked="{isSelected(bonusType,#freeShipping#)}"> Free shipping <br>
<input id="bonusPoints" type="radio" name="bonusType"  value="bonusPoints" checked="{isSelected(bonusType,#bonusPoints#)}"> Bonus points <br>
</blockquote>
<b>Special offer name</b> (shown in the offer list in Admin zone): <input type="text" name="title" value="{title:r}" size="40">
<widget class="\XLite\Validator\RequiredValidator" field="title">
<p align="center">
<a href="javascript: document.offerForm.submit()"><FONT class=FormButton>Next <img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"></FONT></a>
</p>
</form>
{if:isSelected(conditionType,#eachNth#)}
<script>
	eachNthChange(true)
</script>
{end:}
