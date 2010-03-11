{* Recently viewed menu body *}
<ul class="list-body-sidebar">

  <li class="item" FOREACH="getSideBarData(),product">

    <a IF="isShowThumbnails()&product.hasThumbnail()" class="product-thumbnail" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^product.category.category_id))}" IF="product.hasImage()"><img src="{product.getThumbnailURL()}" width="50" alt="" /></a>

    <div class="body">

        <a class="product-name" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^product.category.category_id))}">{product.name:h}</a>
        <br />

        <widget class="XLite_View_Price" product="{product}" displayOnlyPrice="true" IF="isShowPrice()" />
        <widget class="XLite_View_BuyNow" product="{product}" IF="isShowAdd2Cart()" />

    </div>

  </li>

  <li IF="isShowMoreLink()">
    <a class="link" href="{getMoreLinkURL()}" onClick="this.blur()">{getMoreLinkText()}</a>
  </li>

</ul>
