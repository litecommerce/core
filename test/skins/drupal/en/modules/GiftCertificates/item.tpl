{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Gift certificate cart item
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<td class="delete-from-list">
  <widget class="XLite_View_Form_Cart_Item_Delete" name="itemRemove" item="{item}" cartId="{cart_id}" />
    <widget class="XLite_View_Button_Image" label="Delete item" />
  <widget name="itemRemove" end />
</td>

<td class="item-thumbnail">
  <a href="{buildURL(#gift_certificate#,##,_ARRAY_(#gcid#^item.gcid))}"><img src="images/modules/GiftCertificates/gift_certificate.png" alt="Gift certificate" /></a>
</td>

<td class="item-info">
  <div class="item-title"><a href="{buildURL(#gift_certificate#,##,_ARRAY_(#gcid#^item.gcid))}">Gift certificate</a></div>
  <div class="item-description">From: {item.gc.purchaser}<br />To: {item.gc.recipient}</div>
</td>

<td class="item-actions">

  <div class="item-sums">
    <span class="item-price">{price_format(item,#price#):h}</span>
    <span class="sums-multiply">x</span>
    <span class="item-quantity">1</span>
    <span class="sums-equals">=</span>
    <span class="item-subtotal">{price_format(item,#total#):h}</span>
  </div>

</td>
