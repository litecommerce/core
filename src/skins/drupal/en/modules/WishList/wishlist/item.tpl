{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Wishlist item
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<td class="delete-from-list">
  <widget class="\XLite\Module\WishList\View\Form\Item\Delete" name="wl_remove" item="{item}" />
    <widget class="\XLite\View\Button\Image" label="Remove" action="delete" />
  <widget name="wl_remove" end />
</td>

<td class="item-thumbnail" IF="item.hasImage()">
  <a href="{item.getUrl()}">
    <widget class="\XLite\View\Image" image="{item.getImage()}" alt="{item.name}" maxWidth="75" maxHeight="75" IF="item.getImage()" />
    <img src="{item.imageURL}" alt="{item.name}" IF="!item.getImage()"/>
  </a>
</td>

<td class="item-info">

  {displayViewListContent(#wishlist.item.info#,_ARRAY_(#item#^item))}

</td>

<td class="item-actions">

  {displayViewListContent(#wishlist.item.actions#,_ARRAY_(#item#^item))}

</td>
