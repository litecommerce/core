{* SVN $Id$ *}
<div>

  <div class="name">{product.name}</div>

  <div IF="product.hasImage()" class="image">
    <img src="{product.imageURL}" alt="" />
  </div>

  <div IF="{product.sku}" class="sku">
    <strong>SKU:</strong>
    <span>{product.sku}</span>
  </div>

  <widget class="XLite_View_Price" product="{product}" template="common/price_plain.tpl" />

  <br />

  <widget template="buy_now.tpl" product="{product}" />

</div>
