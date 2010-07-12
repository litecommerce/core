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
<h1>1. Condition options</h1>
<form action="admin.php" method="POST" name="offerForm" onSubmit='javascript: if(!checkDate()) return false;'>
<input type="hidden" name="action" value="update2">
<input type="hidden" name="mode" value="{mode}">
<input type="hidden" name="target" value="{target}">
<input type="hidden" name="offer_id" value="{offer_id}">

{if:conditionType=#productSet#}
<h2>Customer buys a specified set of products</h2>
<table border="0" cellpadding=0 cellspacing=0>
<tr><td class=CenterBorder>
<table border="0" cellpadding=3 cellspacing=1 IF="products">
<tr class=Center><th>Title</th><th>Delete</th></tr>
<tr class=Center FOREACH="products,product"><td>{if:product&!product.exists}<span class="ErrorMessage">A deleted product (#{product.product_id}) has been found!</span>&nbsp;{end:}{product.name}</td><td><input type="checkbox" name="deleteProduct[{product.product_id}]"></td></tr>
</table>
</td></tr>
</table>
Add product: <widget class="\XLite\View\ProductSelect" formName="offerForm" formField="addProduct" removeButton>
{end:}

{if:conditionType=#productAmount#}
<h2>Customer buys a certain quantity of a product</h2>
<table border="0">
<tr><td>All Products:</td><td><input type="checkbox" name="allProducts" checked="{allProducts}" value="1"></td></tr>
<tr><td>Product:</td><td>{if:product&product.product_id&!product.exists}<span class="ErrorMessage">A deleted product (#{product.product_id}) has been found!</span>&nbsp;{end:}<widget class="\XLite\View\ProductSelect" formName="offerForm" formField="product" removeButton></td></tr>
<tr><td>Category:</td><td>{if:category&category.category_id&!category.exists}<span class="ErrorMessage">A deleted category (#{category.category_id}) has been found!</span>&nbsp;{end:}<widget class="\XLite\View\CategorySelect" fieldName="category_id" noneOption></td></tr>
<tr>
	<td>Quantity:</td>
	<td>
		<input type="text" size="5" name="amount" value="{amount}"> 
        <widget class="\XLite\Validator\PatternValidator" template="modules/Promotion/amount_validator.tpl" field="amount" pattern="/^[1-9][0-9.]*$/">
	</td>
</tr>
</table>
{end:}

{if:conditionType=#eachNth#}
<h2>Every Nth product purchased</h2>
<table border="0">
<tr><td>Product:</td><td>{if:product&product.product_id&!product.exists}<span class="ErrorMessage">A deleted product (#{product.product_id}) has been found!</span>&nbsp;{end:}<widget class="\XLite\View\ProductSelect" formName="offerForm" formField="product" removeButton></td></tr>
<tr><td>Category:</td><td>{if:category&category.category_id&!category.exists}<span class="ErrorMessage">A deleted category (#{category.category_id}) has been found!</span>&nbsp;{end:}<widget class="\XLite\View\CategorySelect" fieldName="category_id" noneOption></td></tr>
<tr>
	<td>N:</td>
	<td>
		<input type="text" size="5" name="amount" value="{amount}"> 
        <widget class="\XLite\Validator\PatternValidator" template="modules/Promotion/amount_validator.tpl" field="amount" pattern="/^[1-9][0-9.]*$/">
	</td>
</tr>
</table>
{end:}

{if:conditionType=#orderTotal#}
<h2>Order total exceeds a certain amount</h2>
Minimum order subtotal: <input type="text" name="amount" value="{amount}" size="6"><widget class="\XLite\Validator\PatternValidator" template="modules/Promotion/amount_validator.tpl" field="amount" pattern="/^[1-9][0-9.]*$/"><br>
{end:}

{if:conditionType=#bonusPoints#}
<h2>Customer earns a certain number of bonus points</h2>
Number of points granted: <input type="text" name="amount" value="{amount}" size="6"><br>
{end:}

{if:conditionType=#hasMembership#}
<h2>Customer has a certain membership</h2>

<table border=0 cellpadding=0 cellspacing=0>
<tr>
	<td align="center" valign="top">Membership:<br><i>To (un)select more than one<br> membership, Ctrl-click it</i></td> 
	<td>&nbsp;<select multiple size="10" name=customer_memberships[]>
	<option FOREACH="config.Memberships.memberships,membership" selected="{isSelectedMembership(membership)}">{membership}</option>
    </select>
	<td>
		<widget class="\XLite\Module\Promotion\Validator\PromotionMembershipValidator" field="customer_memberships"> 
	</td>
</tr>
</table>
{end:}

<h1>2. Bonus options</h1>

{if:bonusType=#discounts#}
<h2>Discount on a category and/or products</h2>
Discount amount:
<input type="test" name="bonusAmount" value="{bonusAmount}"> 
<input type="radio" name="bonusAmountType" checked="{bonusAmountType=#%#}" value="%"> % or 
<input type="radio" name="bonusAmountType" checked="{bonusAmountType=#$#}" value="$"> $
<br>
<br><b>On all products:</b> <input type="checkbox" name="bonusAllProducts" checked="{bonusAllProducts}" value="1">
<p>
<b>On the following products:</b>
<span IF="!bonusProducts">
<widget class="\XLite\View\ProductSelect" formName="offerForm" formField="addBonusProduct">
</span>
<span IF="bonusProducts">
<table border="0" cellpadding=0 cellspacing=0>
<tr><td class=CenterBorder>
<table border="0" cellpadding=3 cellspacing=1>
<tr class=Center><th>Title</th><th>Delete</th></tr>
<tr class=Center FOREACH="bonusProducts,product"><td>{if:product&!product.exists}<span class="ErrorMessage">A deleted product (#{product.product_id}) has been found!</span>&nbsp;{end:}{product.name}</td>
<td>
	<input type="checkbox" name="deleteBonusProduct[{product.product_id}]">
</td></tr>
</table>
</td></tr>
</table>
Add product: <widget class="\XLite\View\ProductSelect" formName="offerForm" formField="addBonusProduct">
</span>
<p>
<b>On the following category:</b>{if:bonusCategory&!bonusCategory.exists}&nbsp;<span class="ErrorMessage">A deleted category (#{bonusCategory.category_id}) has been found!</span>&nbsp;{end:} <widget class="\XLite\View\CategorySelect" fieldName="bonusCategory_id" noneOption>
{end:}

{if:bonusType=#specialPrices#}
<h2>Specially-priced/free product</h2>
<table border="0" cellpadding=0 cellspacing=0>
<tr><td class=CenterBorder>
<table border="0" cellpadding=3 cellspacing=1>
<tr class=Center><th>Product</th><th>Category</th><th><span IF="bonusPrices">Shop price</span></th><th>Bonus price</th><th>type</th><th><span IF="bonusPrices">Delete</span></th></tr>
<tr class=Center FOREACH="bonusPrices,price">
	<td>{if:price.product&!price.product.exists}<span class="ErrorMessage">A deleted product (#{price.product.product_id}) has been found!</span>&nbsp;{end:}{price.product.name}</td>
	<td>{if:price.category&!price.category.exists}<span class="ErrorMessage">A deleted category (#{price.category.category_id}) has been found!</span>&nbsp;{end:}{price.category.name}</td>
	<td>{if:price.product_id}{price_format(price.product.price):h}{end:}</td>
	<td><input type="text" size="6" value="{price.price}" name="changeBonusPrice[{price.product_id}_{price.category_id}]"></td>
	<td>{price.bonusType}</td>
	<td>
	<input type="checkbox" name="deleteBonusPrice[{price.product_id}_{price.category_id}]">
	</td>
</tr>
<tr class=Center align=center><td colspan="6"><b>Add Bonus Price:<b></td></tr>
<tr class=Center>
	<td><widget class="\XLite\View\ProductSelect" formName="offerForm" formField="addBonusPriceProduct"></td>
	<td><widget class="\XLite\View\CategorySelect" fieldName="addBonusPriceCategory_id" noneOption></td>
	<td>&nbsp;</td>
	<td><input type="text" name="addBonusPrice" size="6" value="0"></td>
	<td><select name="addBonusType"><option>$</option><option>%</option></select></td>
	<td>&nbsp;</td>
</tr>
</table>

</td>
</tr>
</table>
{end:}

{if:bonusType=#freeShipping#}
<h2>Free shipping</h2>
<script>
//<!--
function addVal()
{
    var select = document.offerForm.elements['bonusCountriesSelect'];
    var input = document.offerForm.elements['bonusCountries'];
    selectedValue = select.options[select.selectedIndex].text;
    if (input.value == '') {
        input.value = selectedValue;
    } else {
        input.value = input.value+','+selectedValue;
    }
    select.selectedIndex = 0;
}
//-->
</script>
Free shipping to:<br>
<table>
    <tr>
        <td>
            <input type="radio" name="bonusAllCountries" value="1" checked="{bonusAllCountries=1}">
        </td>
        <td>all countries</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>
            <input type="radio" name="bonusAllCountries" value="0" checked="{bonusAllCountries=0}">
        </td>
        <td>following countries:</td>
        <td>
            <input type="text" name="bonusCountries" value="{bonusCountries}" size="60">
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td align=right> select country: </td>
        <td>
            <select name="bonusCountriesSelect" onChange="addVal()">
                <option>-- select --</option>
                <option FOREACH="countries,country">{country.country}</option>
            </select>
        </td>
    </tr>
</table>

{end:}

{if:bonusType=#bonusPoints#}
<h2>Bonus points</h2>
Number of points granted: <input type="text" name="bonusAmount" value="{bonusAmount}">
<br>
{end:}

<h1>3. Promotion period</h1>
<script> 
    function checkDate()
    {
        var start_year  = parseInt(document.offerForm.start_dateYear.value);
		var start_month = parseInt(document.offerForm.start_dateMonth.value);
		var start_day	= parseInt(document.offerForm.start_dateDay.value);
        var end_year  = parseInt(document.offerForm.end_dateYear.value);
        var end_month = parseInt(document.offerForm.end_dateMonth.value);
        var end_day   = parseInt(document.offerForm.end_dateDay.value);
		if ((start_year > end_year) || (start_year == end_year && start_month > end_month) || (start_year == end_year && start_month == end_month && start_day > end_day)) 
		{
			alert('Start date of the offer is greater than End date');
			return false;
		} 
		return true;
    }
</script>
<table border="0" cellpadding=0 cellspacing=0>
	<tr>
		<td>
			Start date of the offer: 
		</td>
		<td>
			&nbsp;<widget class="\XLite\View\Date" field="start_date" value="{start_date}"> 
		</td>
	</tr>
	<tr>
		<td>
			End date of the offer:
		</td>
		<td>
			 &nbsp;<widget class="\XLite\View\Date" field="end_date" value="{end_date}">
		</td>
	</tr>
</table> 
<p align="center">
<input type="submit" value=" Update ">
</p>
<P ALIgn="center">
<a href="admin.php?target=SpecialOffer&offer_id={offer_id}"><img src="images/back.gif" width="13" height="13" border="0" align="absmiddle"> <FONT class=FormButton>Cancel &amp; Back</FONT></a>
</p>
</form>
