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
<script language="JavaScript">
<!--
function visibleBox(id, skipOpenClose)
    {
        elm1 = document.getElementById("open" + id);
        elm2 = document.getElementById("close" + id);
        if (elm1.style.display == "") {
            elm1.style.display = "none";
            elm2.style.display = "";
        } else {
            elm1.style.display = "";
            elm2.style.display = "none";
        }
    }
// -->
</script>
<tr IF="order.orderAppliedBonuses">
	<td colspan=2>
    	<table width=100% border=0 cellpadding=3 cellspacing=0>
        	<tr>
            	<td colspan=2 nowrap bgcolor="#DDDDDD"><b>Special Offers Applied :</b></td>
             </tr>
            <tr FOREACH="order.orderAppliedBonuses,key,bonus">
 	           <td colspan=2>
					<table width=100% cellpadding=3 cellspacing=0 border=0>
        	        	<tr>
            	        	<td id="close{key}" style="cursor: hand;" onClick="visibleBox('{key}')" colspan=2>
                	           <b>{bonus.title} </b><img src="images/modules/Promotion/open.gif">
                           	</td>
                    	   	<td id="open{key}" style="display: none;" colspan=2>
								<table cellspacing=0 cellpadding=0 border=0>
                            	<tr>
                           			<td  colspan=2 style="cursor: hand;" onClick="visibleBox('{key}')">
                               		<b>{bonus.title} </b><img src="images/modules/Promotion/close.gif">
                           			</td>
                            	</tr>
                            	{if:bonus.conditionType=#productAmount#}
                            	<tr>
                                	<td width="30%"><b>Conditions:</b></td>
                                	<td>
                                	Customer buys a certain quantity of a product 
                                	</td>
                            	</tr>
                            	{end:}
                            	{if:bonus.conditionType=#orderTotal#}
                            	<tr>
                                	<td width="30%"><b>Conditions:</b></td>
                                	<td>
                                	Order subtotal exceeds a certain amount
                                	</td>
                            	</tr>
                            	{end:}
                            	{if:bonus.conditionType=#productSet#}
                            	<tr>
                                	<td width="30%"><b>Conditions:</b></td>
                                	<td>
                                	Customer buys a specified set of products
                                	</td>
                            	</tr>
                            	{end:}
                            	{if:bonus.conditionType=#bonusPoints#}
                            	<tr>
                                	<td width="30%"><b>Conditions:</b></td>
                                	<td>
                                	Customer earns a certain number of bonus points 
                                	</td>
                            	</tr>
                            	{end:}
                            	{if:bonus.conditionType=#eachNth#}
                            	<tr>
                                	<td width="30%"><b>Conditions:</b></td>
                                	<td>
                                	Every Nth product purchased
                                	</td>
                            	</tr>
                            	{end:}
                            	{if:bonus.conditionType=#hasMembership#}
                            	<tr>
                                	<td width="30%"><b>Conditions:</b></td>
                                	<td>
                                	Customer has a certain membership
                                	</td>
                            	</tr>
                            	{end:}
                            	<tr FOREACH="bonus.condition,cond_key,value">
                                	<td>{cond_key:h}:</td>
                                	<td>{value:h}</td>
                            	</tr>
                            	{if:bonus.bonusType=#discounts#}
                            	<tr>
                                	<td width="30%"><b>Bonuses:</b></td>
                                	<td>
                                    	Discount on a category and/or products
                                	</td>
                            	</tr>
                            	{end:}
                            	{if:bonus.bonusType=#specialPrices#}
                            	<tr>
                                	<td width="30%"><b>Bonuses:</b></td>
                                	<td>
                                    	Specially-priced/free product
                                	</td>
                            	</tr>
                            	{end:}
                            	{if:bonus.bonusType=#freeShipping#}
                            	<tr>
                                	<td width="30%"><b>Bonuses:</b></td>
                                	<td>
                                    	Free shipping
                                	</td>
                            	</tr>
                            	{end:}
                            	{if:bonus.bonusType=#bonusPoints#}
                            	<tr>
                                	<td width="30%"><b>Bonuses:</b></td>
                                	<td>
                                    	Bonus points
                                	</td>
                            	</tr>
                            	{end:}
                            	<tr FOREACH="bonus.bonus,bonus_key,value">
                                	<td width="30%">{bonus_key:h}:</td>
                                	<td>{value:h}</td>
                            	</tr>
  								</table>
							</td>
						</tr> 
					</table>
                </td>
            </tr>
        </table>
    </td>
</tr>

<tbody IF="order.DC">
<tr>
	<td nowrap bgcolor="#DDDDDD" colspan=2><b>Discount Coupon</b></td>
<tr>
<tr>
    <td nowrap>Coupon:</td>
    <td>{order.DC.coupon:h}</td>
</tr>
<tr>
    <td nowrap><b>Discount:</b></td>
    <td IF="order.DC.type=#absolute#">{price_format(order.DC.discount):h}</td>
    <td IF="order.DC.type=#percent#">{order.DC.discount}%</td>
    <td IF="order.DC.type=#freeship#">Free shipping</td>
</tr>
</tbody>
