{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Wishlist
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
{if:absentOptions}
  <p>Sorry, but some options of "{invalidProductName:h}" do not exist anymore and you can not add this product to the cart.</p>
  <p><a href="javascript: history.go(-1)">Go back</a></p>
{else:}
	{if:invalidOptions}
    <p>Sorry, but options of "{invalidProductName:h}" are invalid. You coudn't add product to cart.</p>
    <p><a href="javascript: history.go(-1)">Go back</a></p>
  {else:}
    <div id="wish-list">
      
      <table class="selected-products">
        <tbody>
          <tr class="selected-product" FOREACH="getItems(),key,item">
            <widget template="modules/WishList/wishlist/item.tpl" key="{key}" item="{item}">
          </tr>
        </tbody>
      </table>

      <div class="wishlist-send">
        <widget template="modules/WishList/send_wishlist.tpl" />
      </div>

      <div class="wishlist-buttons">
        <widget class="XLite_View_Button_Link" label="Clear Wish List" location="{buildURL(#wishlist#,#clear#)}" />
      </div>

    </div>
	{end:}
{end:}
