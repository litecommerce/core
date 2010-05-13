{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Price widget
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
{if:isDisplayOnlyPrice()}

  <span class="price product-price">{price_format(getProduct(),#listPrice#):h}</span>

{else:}

  <div class="price product-price">{price_format(getProduct(),#listPrice#):h}</div>

  <div IF="{isSalePriceEnabled()}" class="product-market-price">
    List price: <span class="price">{price_format(getProduct(),#sale_price#):h}</span><span IF="{isSaveEnabled()}">, you save: <span class="save">{getSaveValueAbsolute()} ({getSaveValuePercent()}%)</span></span>
  </div>

{end:}
