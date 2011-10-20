{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Market price (internal list element)
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.9
 *
 * @ListChild (list="product.details.page.info.market_price.text", weight="100")
 * @ListChild (list="product.details.quicklook.info.market_price.text", weight="100")
 *}

{t(#Market price#)}: <span class="value">{formatPrice(product.getMarketPrice()):h}</span>
