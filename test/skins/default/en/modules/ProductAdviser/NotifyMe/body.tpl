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
<form action="{getShopUrl(#cart.php#)}" method="POST" name="notify_me_form">
<input type="hidden" name="target" value="notify_me">
<input type="hidden" name="action" value="{action}">
<input type="hidden" name="mode" value="{mode}">
<input type="hidden" name="url" value="{prevUrl}">
<span IF="action=#notify_product#">
<input type="hidden" name="product_id" value="{product_id}">
<span IF="productOptions">
<span FOREACH="productOptions,option">
<input type="hidden" name="product_options[{option.class:h}][option_id]" value="{option.option_id}">
<input type="hidden" name="product_options[{option.class:h}][option]" value="{option.option}">
</span>
</span>
<input type="hidden" name="amount" IF="amount" value="{amount}"/>
</span>
<span IF="action=#notify_price#">
<input type="hidden" name="product_id" value="{product_id}">
<input type="hidden" name="product_price" value="{product_price}">
</span>
<table border=0 width="100%" cellpadding=5>
	<tbody IF="action=#notify_product#">
	<tr class="TableHead">
		<td IF="isEmpty(amount)"><b>... the product is in stock</b></td>
		<td IF="!isEmpty(amount)"><b>... the stock quantity of a product increases</b></td>
	</tr>
	</tbody>
	<tbody IF="action=#notify_price#">
	<tr class="TableHead">
		<td><b>... the price drops</b></td>
	</tr>
	</tbody>
	<tbody>
	<tr>
		<td>
            <table cellpadding="5" cellspacing="0" border="0">
            <tr>
                <td IF="config.General.show_thumbnails" valign="top" align="center" width="80">
                <!-- Product thumbnail -->
					<span IF="action=#notify_product#">
                    <span IF="product.hasThumbnail()"><a href="cart.php?target=product&amp;product_id={product.product_id}&amp;category_id={category_id}"><img src="{product.thumbnailURL}" border=0 width=70 alt=""></a></span>
                    </span>
					<span IF="action=#notify_price#">
                    <span IF="product.hasThumbnail()"><a href="cart.php?target=product&amp;product_id={product.product_id}&amp;category_id={category_id}"><img src="{product.thumbnailURL}" border=0 width=70 alt=""></a></span>
                    </span>
                    <br><br>
                </td>
                <td valign="top">
                <!-- Product details -->
                    <table border="0">
                        <tr class="ProductDetails">
                            <td>Product:</td>
                    		<td>&nbsp;</td>
                            <td><b>{product.name:h}</b></td>
                        </tr>
                    	<tr IF="productOptions" class="ProductDetails">
                    		<td>Options:</td>
                    		<td>&nbsp;</td>
                    		<td><b>{productOptionsStr}</b></td>
                    	</tr>
                        <tr class="ProductDetails" IF="amount">
                            <td>Quantity:</td>
                    		<td>&nbsp;</td>
                            <td>more than <b>{amount}</b></td>
                        </tr>
                        <tr class="ProductDetails" IF="action=#notify_price#">
                            <td>Price:</td>
                    		<td>&nbsp;</td>
                            <td><b>{price_format(product,#listPrice#):h}<font class="ProductPriceTitle"> {product.priceMessage:h}</font></b></td>
                        </tr>
                    </table>
                </td>
            </tr>
            </table>
		</td>
	</tr>
	<tr>
		<td><hr></td>
	</tr>
	<tr IF="!xlite.auth.logged">
		<td>
		If you already have an account, you can authenticate yourself by <a href="cart.php?target=profile&amp;mode=login&amp;from=notify_me"><b><u>logging in here</u></b></a>.<br>
		If you do not have an account, you can easily <a href="cart.php?target=profile&amp;mode=register&amp;from=notify_me"><b><u>register here</u></b></a>.
		</td>
	</tr>
	<tr>
		<td>
            <table cellpadding="5" cellspacing="0" border="0">
            <tr>
                <td>Your e-mail:</td>
                <td><font class="Star">*</font></td>
                <td IF="!xlite.auth.logged">
					<table cellpadding="0" cellspacing="0" border="0">
                    	<tr>
                    		<td><input type=text size=30 name="email" value="{email}"></td>
                    		<td><widget class="XLite_Validator_EmailValidator" field="email"></td>
                    	</tr>
					</table>
                </td>
                <td IF="xlite.auth.logged">
                <input type=text size=30 value="{xlite.auth.profile.login}" disabled>
                <input type=hidden size=30 name="email" value="{xlite.auth.profile.login}">
                </td>
            </tr>
            <tr>
                <td>Your name:</td>
                <td>&nbsp;</td>
                <td>
                <input type=text size=50 name="person_info" IF="!xlite.auth.logged"/>
                <input type=text size=50 name="person_info" IF="xlite.auth.logged" value="{xlite.auth.profile.billing_title} {xlite.auth.profile.billing_firstname} {xlite.auth.profile.billing_lastname}"/>
                </td>
            </tr>
        	<tr>
                <td colspan=2>&nbsp;</td>
        		<td>Mandatory fields are marked with an asterisk (<font class="Star">*</font>).</td>
        	</tr>
        	<tr>
                <td colspan=2>&nbsp;</td>
        		<td><widget class="XLite_View_Button_Submit" label="Notify me" /></td>
        	</tr>
            </table>
		</td>
	</tr>
	</tbody>
</table>
</form>
