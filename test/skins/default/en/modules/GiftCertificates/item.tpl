{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * CArt item
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<table cellpadding="5" cellspacing="0" width="100%" IF="item.gcid">

  <tr>
    <td valign="top" width="70">
      <a href="{buildURL(#gift_certificate#,##,_ARRAY_(#gcid#^item.gcid))}"><img src="images/modules/GiftCertificates/gift_certificate.gif" alt="" /></a>
    </td>
    <td>
      <a href="{buildURL(#gift_certificate#,##,_ARRAY_(#gcid#^item.gcid))}"><font class="ProductTitle">Gift certificate</font></a>
      <br />
      <br />
		  From: {item.gc.purchaser}<br />
		  To: {item.gc.recipient}<br />
      <br />
        
      <font class="ProductPriceTitle">Amount:</font>
      <font class="ProductPriceConverting">{price_format(item,#price#):h}</font>
      <br />
      <br />
      <widget class="XLite_View_Button_Link" location="{buildURL(#gift_certificate#,##,_ARRAY_(#gcid#^item.gcid))}" label="Modify certificate" />
      &nbsp;&nbsp;&nbsp;
      <widget class="XLite_View_Button_Link" href="{buildURL(#cart#,#delete#,_ARRAY_(#cart_id#^cart_id))}" label="Delete item" />
    </td>
  </tr>

</table>
