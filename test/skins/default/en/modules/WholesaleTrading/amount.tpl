{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product quantity input widget
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<script type="text/javascript">
$(document).ready(
  function() {
    new productQuantityController($('.product-quantity.product-{product.product_id}'), {getMinAmount()}, {getMaxAmount()})
  }
);
</script>

<div class="product-quantity product-{product.product_id}">
  <label for="product_quantity_{product.product_id}">Quantity:</label>
  <a href="javascript:void(0);" class="quantity-lower"><img src="images/spacer.gif" alt="-" /></a>
  <input id="product_quantity_{product.product_id}" name="amount" value="{getMinAmount()}" />
  <a href="javascript:void(0);" class="quantity-upper"><img src="images/spacer.gif" alt="+" /></a>
  {if:hasAmountRegion()}
    <span>({getMinAmount()}&ndash;{getMaxAmount()})</span>
  {else:}
    <span IF="hasMinAmount()">(min. {getMinAmount()})</span>
  {end:}
</div>
