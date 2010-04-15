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
<table width="90%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr IF="order.orderAppliedBonuses">
	<td colspan=2><b>Special Offers Applied:</b></td>
</tr>
<tr FOREACH="order.orderAppliedBonuses,key,bonus">
	<td colspan=2>
		<table cellspacing=0 cellpadding=1 border=0>
      	<tr>
   			<td colspan=2><b><u>{bonus.title}</u></b></td>
       	</tr>
       	<tr>
         	<td width="30%"><b>Conditions:</b></td>
           	<td IF="bonus.conditionType=#productAmount#">Customer buys a certain quantity of a product</td>
           	<td IF="bonus.conditionType=#orderTotal#">Order total exceeds a certain amount</td>
        	<td IF="bonus.conditionType=#productSet#">Customer buys a specified set of products</td>
           	<td IF="bonus.conditionType=#bonusPoints#">Customer earns a certain number of bonus points</td>
           	<td IF="bonus.conditionType=#eachNth#">Every Nth product purchased</td>
          	<td IF="bonus.conditionType=#hasMembership#">Customer has a certain membership</td>
		</tr>
       	<tr FOREACH="bonus.condition,cond_key,value">
           	<td>{cond_key:h}:</td>
           	<td>{value:h}</td>
      	</tr>
       	<tr>
           	<td width="30%"><b>Bonuses:</b></td>
           	<td IF="bonus.bonusType=#discounts#">Discount on a category and/or products</td>
           	<td IF="bonus.bonusType=#specialPrices#">Specially-priced/free product</td>
        	<td IF="bonus.bonusType=#freeShipping#">Free shipping</td>
			<td IF="bonus.bonusType=#bonusPoints#">Bonus points</td>
       	</tr>
      	<tr FOREACH="bonus.bonus,bonus_key,value">
           	<td width="30%">{bonus_key:h}:</td>
           	<td>{value:h}</td>
       	</tr>
		</table>
	</td>
</tr> 
</table>
