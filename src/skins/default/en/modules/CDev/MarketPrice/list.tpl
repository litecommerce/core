{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Item market price
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.product.grid.customer.info", weight="25")
 * @ListChild (list="itemsList.product.small_thumbnails.customer.details", weight="25")
 * @ListChild (list="itemsList.product.list.customer.info", weight="35")
 * @ListChild (list="productBlock.info", weight="250")
 *}

<div IF="isShowMarketPrice(product)" class="product-list-market-price">
  {formatPrice(product.getMarketPrice(),null,1)}
</div>
