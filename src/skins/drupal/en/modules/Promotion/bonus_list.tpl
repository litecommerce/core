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
<img src="images/modules/Promotion/bonus_list.gif" width=59 height=48 border=0 align=left vspace=5 hspace=5>
<table border="0" cellpadding="5">
<tbody FOREACH="bonusList,bonus">
<tr IF="isShowBonus(bonus)">
	<td colspan="2">
	<!-- bonus condition -->
	<p class=Promotion>
        <span IF="bonus.conditionType=#productSet#">
	You have ordered products: <span FOREACH="bonus.products,product"><FONT color=#ff0000>{product.name}</FONT>, </span>
	</span>
	<span IF="bonus.conditionType=#productAmount#">
	{if:bonus.allProducts}
	You have ordered <FONT color=#ff0000>{integer(bonus.amount)}</FONT> or more items,
	{else:}
	<span IF="bonus.product&bonus.allBonusProducts">
	You have ordered <FONT color=#ff0000>{integer(bonus.amount)}</FONT> or more items of "<FONT color=#ff0000>{bonus.product.name}</FONT>", 
	</span>
	<span IF="bonus.category">You have ordered <FONT color=#ff0000>{integer(bonus.amount)}</FONT> or more items from the <FONT color=#ff0000>"{bonus.category.name}"</FONT> category, </span>
	{end:}
	</span>
	<span IF="bonus.conditionType=#eachNth#">
	For each <FONT color=#ff0000>{integer(bonus.amount)}th</FONT> <span IF="bonus.product_id">"<FONT color=#ff0000>{bonus.product.name}</FONT>", </span> <span IF="bonus.category_id">product from <FONT color=#ff0000>"{bonus.category.name}"</FONT> category, </span>
	the following products come at <FONT color=#ff0000>special</FONT> prices:
	</span>
	<span IF="bonus.conditionType=#orderTotal#">
	Your order has exceeded <FONT color=#ff0000>{price_format(bonus.amount):h}</FONT>
	</span>
    <span IF="bonus.conditionType=#hasMembership#">
    You have <FONT color=#ff0000>{bonus.membership}</FONT> membership,
    </span>
	<span IF="bonus.conditionType=#bonusPoints#">
	Your have <FONT color=#ff0000>{bonus.amount}</FONT> or more bonus points,
	</span>
	<span IF="bonus.bonusType=#bonusPoints#">
	you will get <FONT color=#ff0000>{integer(bonus.bonusAmount)}</FONT> bonus points.
	</span>
	<span IF="!bonus.conditionType=#eachNth#">
	<span IF="!bonus.bonusType=#bonusPoints#">
	<span IF="bonus.product">
	<span IF="bonus.allBonusProducts">
	therefore you can purchase the following products at <FONT color=#ff0000>special</FONT> prices:
	</span>
	</span>
	<span IF="!bonus.product">
	therefore you can purchase the following products at <FONT color=#ff0000>special</FONT> prices:
	</span>
	</span>
	</span>
	</p>
	</td>
</tr>
<tr FOREACH="bonus.allBonusProducts,product">
    <td IF="config.General.show_thumbnails" valign="top" align="center" width="80">
    <!-- Product thumbnail -->
        <a href="cart.php?target=product&amp;product_id={product.product_id}&amp;substring={substring:u}" IF="product.hasThumbnail()"><img src="{product.thumbnail.url}" border=0 width=70></a> <br><a href="cart.php?target=product&product_id={product.product_id}&substring={substring:u}" IF="product.hasThumbnail()">See&nbsp;details&nbsp;<img src="images/details.gif" width="13" height="13" border="0" align="absmiddle"></a>
        <br><br>
    </td>
    <td valign="top">
    <!-- Product details -->
        <a href="cart.php?target=product&amp;product_id={product.product_id}&amp;substring={substring:u}"><FONT class="ProductTitle">{product.name:h}</FONT></a>
        <br>
        <br>
        {truncate(product.brief_description,#300#):h}
        <br>
        <hr>
        <FONT class="ProductPriceTitle">Price:</FONT> <s><FONT class="ProductPrice">{price_format(product.listPrice):h}</FONT></s> <FONT class="ProductPrice">{price_format(bonus.getBonusTaxedPrice(product,product.listPrice)):h}</FONT><br><br>
        <widget template="buy_now.tpl" product="{product}">
		<br><br>
    </td>
</tr>

<tr FOREACH="bonus.allBonusCategories,category">
    <td valign="top" align="center" width="80">
    <!-- Category thumbnail -->
        <a href="cart.php?target=category&amp;category_id={category.category_id}" IF="category.hasImage()"><img src="{category.image.url}" border="0"></a> <br><a href="cart.php?target=category&amp;category_id={category.category_id}" IF="category.hasImage()">See&nbsp;details&nbsp;<img src="images/details.gif" width="13" height="13" border="0" align="absmiddle"></a>
        <br><br>
    </td>
    <td valign="top">
    <!-- Category details -->
        <a href="cart.php?target=category&amp;category_id={category.category_id}"><FONT class="ProductTitle">{category.name}</FONT></a>
        <br>
        <br>
        {truncate(category.description,#300#):h}
        <br>
        <br>
		<!-- Discount info -->
		<FONT class="ProductPrice"><span IF="bonus.isCategoryDiscount(category)">Discount </span><span IF="!bonus.isCategoryDiscount(category)">Special price </span>
			<span IF="bonus.isCategoryDiscountType(category,#$#)">{price_format(bonus.getCategoryDiscount(category)):h}</span>
			<span IF="bonus.isCategoryDiscountType(category,#%#)">{bonus.getCategoryDiscount(category)} %</span></FONT>
		 &nbsp;&nbsp;
		<a href="cart.php?target=category&amp;category_id={category.category_id}"><font class="FormButton">Browse category &gt;&gt;</FONT></a>

    </td>
</tr>
<tr IF="bonus.bonusAllProducts">
<td>&nbsp;</td>
<td>
	<span IF="bonus.bonusAmountType=#$#">{price_format(bonus.bonusAmount):h}</span>
	<span IF="bonus.bonusAmountType=#%#">{bonus.bonusAmount} %</span>
	discount on all products!
</td>
</tr>
</tbody>
</table>

<center>
<widget class="XLite_View_Button" href="cart.php?target=checkout" label="Continue checkout.." font="FormButton">
</center>
