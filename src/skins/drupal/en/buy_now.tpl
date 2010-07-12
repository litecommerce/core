{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Buy now button
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<widget class="\XLite\View\Form\Product\AddToCart" name="add_to_cart" product="{getProduct()}" />
  <widget class="\XLite\View\Button\Submit" label="Buy now" style="{style}" IF="!isShowPrice()" />
  <widget class="\XLite\View\Button\Submit" label="{price_format(getProduct(),#listPrice#):h}" style="{style} price-button" IF="isShowPrice()" />
<widget name="add_to_cart" end />
