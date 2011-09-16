{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Overlapping box
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="itemsList.product.grid.customer.info", weight="999")
 * @ListChild (list="itemsList.product.list.customer.quicklook", weight="999")
 * @ListChild (list="productBlock.info", weight="999")
 *}
<div class="quicklook">
  <a
    href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}"
    class="quicklook-link quicklook-link-{product.product_id}">
    <div class="quicklook-view">&nbsp;</div>
  </a>
</div>
