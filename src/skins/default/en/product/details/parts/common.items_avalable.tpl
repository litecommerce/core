{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Quantity input box
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="product.details.page.info", weight="18")
 * @ListChild (list="product.details.quicklook.info", weight="18")
 *}

<span class="stock-level product-in-stock" IF="isAvailableLabelVisible()">
  {t(#In stock#)}
  <span class="product-items-available">({t(#X items available#,_ARRAY_(#count#^product.inventory.getAvailableAmount()))})</span>
</span>
