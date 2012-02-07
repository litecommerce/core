{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product price value
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="product.plain_price", weight="10")
 *}

 <li IF="participateSale(product)" class="sale-banner">
   <div class="sale-banner-block">
     <div class="text">{t(#sale#)}</div>
     <div class="percent">{getSalePercent(product)}% off</div>
   </div>
 </li>
