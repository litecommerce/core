{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<widget template="modules/WholesaleTrading/purchase_limit.tpl" visible="{product.purchaseLimit.min|product.purchaseLimit.max}" IF="product.purchaseLimit" />
<widget template="modules/WholesaleTrading/wholesale_pricing.tpl" visible="{product.isPriceAvailable()&product.hasWholesalePricing()}">
<widget template="modules/WholesaleTrading/amount.tpl" visible="product.isPriceAvailable()">
