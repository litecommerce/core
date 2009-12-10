<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
    <td valign="top" width="70">
        <a href="{item.url}" IF="item.hasThumbnail()"><img src="{item.thumbnailUrl}" border="0" width="70" alt=""></a>
    </td>
    <td>
        <a href="{item.url}"><FONT class="ProductTitle">{item.name:h}</FONT></a><br><br>
        {truncate(item.brief_description,#300#):h}<br>
        <br>
        
        <widget module="ProductOptions" template="modules/ProductOptions/selected_options.tpl" visible="{item.hasOptions()}" item="{item}">

        <FONT IF="{item.sku}" class="ProductDetails">SKU: {item.sku}</FONT><br>

		<!-- strikeout price -->
        <FONT class="ProductPriceTitle">Price:</FONT> <FONT class="ProductPriceConverting" IF="item.price=item.parentPrice">{price_format(item.price):h}&nbsp;x&nbsp;</FONT>
		<FONT class="ProductPriceConverting" IF="!item.price=item.parentPrice"><s>{price_format(item.parentPrice):h}</s> {price_format(item.price):h}&nbsp;x&nbsp;</FONT>
		<!-- /strikeout price -->

        <input type="text" name="amount[{cart_id}]" value="{item.amount}" size="3" maxlength="6">
        <FONT class="ProductPriceConverting">&nbsp;=&nbsp;</FONT>
        <FONT class="ProductPrice">{price_format(item.total):h}</FONT>
		<widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/cart_item.tpl" visible="{xlite.PA_InventorySupport}">
        <br>
        <br>
		<table><tr><td>
        <widget class="CButton" label="Delete item" href="cart.php?target=cart&action=delete&cart_id={cart_id}" font="FormButton">
		</td><td>&nbsp;</td><td>
		<widget class="CButton" label="Update item" href="javascript: document.cart_form.action.value='update'; document.cart_form.submit()" font="FormButton">
		</td></tr></table>
        <widget module="GoogleCheckout" template="modules/GoogleCheckout/shopping_cart/item.tpl">
    </td>
	<!-- bonus -->
	<td class="PromotionProductDetailsTitle" IF="!item.price=item.parentPrice" align="right">
		<span IF="item.bonusApplies">
		<span IF="!item.discountCouponApplies">
		<img src="images/modules/Promotion/bonus.gif" alt=""><br>
		<span class="PromotionProductDetailsTitle" IF="item.price=0">Free</span>
		<span class="PromotionProductDetailsTitle" IF="!item.price=0">Bonus price</span>
		</span>
		</span>
		<span IF="item.discountCouponApplies">
		<img src="images/modules/Promotion/bonus.gif" alt=""><br>
		Discount coupon
		</span>
	</td>
	<!-- /bonus -->
</tr>
</table>

