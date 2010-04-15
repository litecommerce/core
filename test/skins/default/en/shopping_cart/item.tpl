{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<table cellpadding="5" cellspacing="0" width="100%">

  <tr>

    <td valign="top" width="70">
      <a href="{item.url}" IF="item.hasThumbnail()"><img src="{item.thumbnailURL}" width="70" alt=""></a>
    </td>

    <td>
      <a href="{item.url}"><font class="ProductTitle">{item.name}</font></a>
      <br />
      <br />
		  {truncate(item.brief_description,#300#):h}<br />
      <br />
        
      <widget module="ProductOptions" template="modules/ProductOptions/selected_options.tpl" visible="{item.hasOptions()}" item="{item}">
		
		  <span IF="{item.weight}">
		    Weight: {item.weight} {config.General.weight_symbol}<br />
		  </span>

      <font IF="{item.sku}" class="ProductDetails">
        SKU: {item.sku}<br />
      </font>

      <font class="ProductPriceTitle">Price:</font>
      <font class="ProductPriceConverting">{price_format(item,#price#):h}&nbsp;x&nbsp;</font>
      <input type="text" name="amount[{cart_id}]" value="{item.amount}" size="3" maxlength="6" />
      <font class="ProductPriceConverting">&nbsp;=&nbsp;</font>
      <font class="ProductPrice">{price_format(item,#total#):h}</font>

      <widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/cart_item.tpl" visible="{xlite.PA_InventorySupport}">
      <br />
      <br />

		  <table>
        <tr>
		  <td><widget class="XLite_View_Button_Regular" label="Delete item" action="delete" formParams="{_ARRAY_(#cart_id#^cart_id)}" /></td>
          <td>&nbsp;</td>
          <td><widget class="XLite_View_Button_Submit" label="Update item" /></td>
        </tr>
      </table>

		  <widget module="GoogleCheckout" template="modules/GoogleCheckout/shopping_cart/item.tpl">

      <span IF="!item.valid">
        <font class="ProductPriceSmall"><br />(!) This product is out of stock or it has been disabled for sale.</font>
      </span>
    </td>

  </tr>

</table>
