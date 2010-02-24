{* SVN $Id$ *}
<tr class="descriptionTitle">
  <td colspan="2" class="ProductDetailsTitle">Wholesale pricing</td>
</tr>

<tr>
  <td class="Line" height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt="" /></td>
</tr>

<tr>
  <td colspan=2>&nbsp;</td>
</tr>

<tr>
  <td colspan="2">

    <table cellpadding="0" cellspacing="0">

      <tr>
      	<td><strong>Quantity</strong></td>
	      <td><strong>Price per product</strong></td>
      </tr>

      <tbody FOREACH="wholesalePricing,idx,wholesale_price">

        <tr>
        	<td class="Line" height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt="" /></td>
        </tr>

        <tr style="height: 18px;"> 
        	<td nowrap>{wholesale_price.amount} or more</td>
	        <td align="right">{price_format(wholesale_price.price):r}</td>
        </tr>

      </tbody>

    </table>

  </td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>
