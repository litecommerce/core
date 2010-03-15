{* $Id$ *}
{if:absentOptions}
  <p>Sorry, but some options of "{invalidProductName:h}" do not exist anymore and you can not add this product to the cart.</p>
  <p><a href="javascript: history.go(-1)">Go back</a></p>
{else:}
	{if:invalidOptions}
    <p>Sorry, but options of "{invalidProductName:h}" are invalid. You coudn't add product to cart.</p>
    <p><a href="javascript: history.go(-1)">Go back</a></p>
  {else:}
    <div id="wish-list" IF="getItems()">
      
      <table class="wishlist-items">
        <tbody>
          <tr class="wishlist-item" FOREACH="getItems(),key,item">
            <widget template="modules/WishList/item.tpl" key="{key}" item="{item}">
          </tr>
        </tbody>
      </table>

      <div class="wishlist-send">
        <widget template="modules/WishList/send_wishlist.tpl">
      </div>

      <div class="wishlist-buttons">
        <widget class="XLite_View_Button_Link" label="Clear Wish List" location="{buildURL(#wishlist#,#clear#)}" />
      </div>

    </div>

    <div IF="!getItems()">
      <p>Your Wish List is empty.</p>
    </div>
	{end:}
{end:}
