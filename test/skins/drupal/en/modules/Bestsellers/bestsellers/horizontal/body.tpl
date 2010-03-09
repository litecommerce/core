{* SVN $Id$ *}
<ul class="{widgetCSSClasses()}">
  <li FOREACH="getBestsellers(),id,product">
    <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id))}" class="product-thumbnail" IF="thumbnailsEnabled()&product.hasThumbnail()"><img src="{product.thumbnailURL}" width="25" alt="" /></a>
    <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id))}" class="product-title">{product.name}</a>
    <p class="product-price">{price_format(product,#price#):h}</p>
    <form class="add-to-cart-form" action="{buildURL(#product#,#buynow#,_ARRAY_(#product_id#^product.product_id))}" method="post">
      <button class="aux-button add-to-cart" type="submit"><span class="aux-button">Add to cart</span></button>
    </form>
  </li>
</ul>
