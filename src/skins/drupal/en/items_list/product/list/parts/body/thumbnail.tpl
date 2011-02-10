{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Item thumbnail
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *
 * @ListChild (list="itemsList.product.list.customer.body", weight="20")
 *}

<td class="hproduct">
  <div class="quick-look-cell">
  <div class="quick-look-cell-thumbnail">
    {displayNestedViewListContent(#quick_look.thumbnail#,_ARRAY_(#product#^product))}
    <a IF="isShowThumbnails()" class="product-thumbnail" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}"><widget class="\XLite\View\Image" image="{product.getImage()}" maxWidth="{getIconWidth()}" maxHeight="{getIconHeight()}" alt="{product.name}" className="photo" /></a>
  </div>
  </div>
</td>
