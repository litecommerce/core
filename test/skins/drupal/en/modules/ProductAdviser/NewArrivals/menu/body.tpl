{* New Arrivals menu body *}
<ul class="list-body-sidebar">

  <li class="item" FOREACH="getNewArrivalsProducts(),id,product">

    <a class="product-thumbnail" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^product.category.category_id))}" IF="product.hasImage()"><img src="{product.imageURL}" width="50" alt="" /></a>
    
    <div class="body">

      <a class="product-name" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^product.category.category_id))}">{product.name:h}</a>
      <br />

      <widget class="XLite_View_Price" product="{product}" displayOnlyPrice="true" />

    </div>

  </li>

  <li IF="additionalPresent">
    <a class="link" href="{buildURL(#new_arrivals#,##)}" onClick="this.blur()">All new arrivals...</a>
  </li>

</ul>
