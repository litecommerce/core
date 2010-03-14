{* SVN $Id$ *}
<div class="delete-from-cart">
  <input type="image" src="images/spacer.gif" />
  {*<widget class="XLite_View_Button" type="button" href="{buildURL(#cart#,#delete#,_ARRAY_(#cart_id#^cart_id))}" label="X" />*}
</div>

<div class="item-thumbnail">
  <a href="{buildURL(#add_gift_certificate#,##,_ARRAY_(#gcid#^item.gcid))}"><img src="images/modules/GiftCertificates/gift_certificate
.gif" alt="" /></a>
</div>

<div class="item-info">
  <div class="item-title"><a href="{buildURL(#add_gift_certificate#,##,_ARRAY_(#gcid#^item.gcid))}">Gift certificate</a></div>
  <div class="item-description">From: {item.gc.purchaser}<br />To: {item.gc.recipient}</div>
</div>

<div class="item-actions">

  <div class="item-sums">
    <span class="item-price">{price_format(item,#price#):h}</span>
    <span class="sums-multiply">x</span>
    <span class="item-quantity">1</span>
    <span class="sums-equals">=</span>
    <span class="item-subtotal">{price_format(item,#total#):h}</span>
  </div>

  <div class="item-buttons">
    <span class="modify-gc"><widget class="XLite_View_Button" type="button_link" href="{buildURL(#add_gift_certificate#,##,_ARRAY_(#gcid#^item.gcid))}" label="Modify certificate"></span>
  </div>

</div>
