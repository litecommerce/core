{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Label (internal list element)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.14
 *
 * @ListChild (list="product.details.page.info", weight="42")
 * @ListChild (list="product.details.quicklook.info", weight="42")
 *}

<div IF="participateSale(product)" class="sale-label-product-details">
  <div class="text">
    <list name="sale_price.text" type="nested" />
  </div>
  <list name="sale_price.label" type="nested" />
</div>
