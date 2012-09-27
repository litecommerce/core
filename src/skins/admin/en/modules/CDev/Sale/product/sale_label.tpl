{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product element
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.products.search.cell.name", weight="10")
 *}

<span
  id="product-sale-label-{entity.getProductId()}"
  class="product-name-sale-label{if:!participateSale(entity)} product-name-sale-label-disabled{end:}"
  >
  {t(#sale#)}
</span>
