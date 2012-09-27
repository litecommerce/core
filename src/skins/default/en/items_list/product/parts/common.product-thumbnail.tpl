{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Item thumbnail
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.product.grid.customer.info.photo", weight="10")
 * @ListChild (list="itemsList.product.small_thumbnails.customer.info.photo", weight="10")
 * @ListChild (list="itemsList.product.big_thumbnails.customer.info.photo", weight="10")
 * @ListChild (list="productBlock.info.photo", weight="100")
 *}
<a
  href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}"
  class="product-thumbnail">
  <widget
    class="\XLite\View\Image"
    image="{product.getImage()}"
    maxWidth="{getIconWidth()}"
    maxHeight="{getIconHeight()}"
    alt="{product.name}"
    className="photo" />
</a>
