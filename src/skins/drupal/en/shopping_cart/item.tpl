{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Cart item widget
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

<td class="item-thumbnail" IF="item.hasThumbnail()">
  <a href="{item.url}">
    <widget class="XLite_View_Image" image="{item.getThumbnail()}" alt="{item.name}" maxWidth="75" maxHeight="75" IF="item.getThumbnail()" />
    <img src="{item.thumbnailURL}" alt="{item.name}" IF="!item.getThumbnail()" />
  </a>
</td>

<td class="item-info">
  {displayViewListContent(#cart.item.info#,_ARRAY_(#item#^item,#cart_id#^cart_id))}
</td>

<td class="item-actions">
  {displayViewListContent(#cart.item.actions#,_ARRAY_(#item#^item,#cart_id#^cart_id))}
</td>
