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
  <widget class="\XLite\View\Form\Cart\Item\Delete" name="itemRemove" item="{item}" />
    <widget class="\XLite\View\Button\Image" label="Delete item" />
  <widget name="itemRemove" end />
</td>

<td class="item-thumbnail" IF="item.hasThumbnail()">
  <a href="{item.getURL()}">
    <widget class="\XLite\View\Image" image="{item.getThumbnail()}" alt="{item.getName()}" maxWidth="75" maxHeight="75" IF="item.getThumbnail()" />
    <img src="{item.getThumbnailURL()}" alt="{item.getName()}" IF="!item.getThumbnail()" />
  </a>
</td>

<td class="item-info">
  {displayViewListContent(#cart.item.info#,_ARRAY_(#item#^item))}
</td>

<td class="item-actions">
  {displayViewListContent(#cart.item.actions#,_ARRAY_(#item#^item,#cart_id#^cart_id))}
</td>
