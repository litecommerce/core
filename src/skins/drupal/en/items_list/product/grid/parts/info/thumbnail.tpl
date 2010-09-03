{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Item thumbnail
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="itemsList.product.grid.customer.info", weight="30")
 *}
<a IF="isShowThumbnails()&product.hasThumbnail()" href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}" class="product-thumbnail url">
  <widget class="\XLite\View\Image" image="{product.getThumbnail()}" maxWidth="{getIconWidth()}" maxHeight="{getIconHeight()}" alt="{product.name}" className="photo" IF="isShowThumbnails()" />
</a>
