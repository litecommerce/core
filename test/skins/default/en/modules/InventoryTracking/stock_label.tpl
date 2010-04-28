{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product quantity label
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div IF="{product.isInStock()}" class="product-stock-label product-in-stock">In stock</div>
<div IF="{product.isOutOfStock()}" class="product-stock-label product-out-stock">Out of stock</div>
<widget module="ProductAdviser"  class="XLite_Module_ProductAdviser_View_NotifyLink" />
