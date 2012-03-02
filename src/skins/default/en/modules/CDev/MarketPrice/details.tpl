{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product market price
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.9
 *
 * @ListChild (list="product.details.page.info", weight="45")
 * @ListChild (list="product.details.quicklook.info", weight="45")
 *}

<div IF="isShowMarketPrice(product)" class="product-details-market-price">
  <div class="text">
    <list name="market_price.text" type="nested" />
  </div>
  <list name="market_price.label" type="nested" />
</div>
