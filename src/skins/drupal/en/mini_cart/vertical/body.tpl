{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Vertical minicart
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div id="lc-minicart-{getParam(%static::PARAM_DISPLAY_MODE%)}" class="lc-minicart-{getParam(%static::PARAM_DISPLAY_MODE%)} {collapsed}">

  <div class="cart-link">
    <h2><a href="{buildURL(#cart#)}">Your cart</a></h2>
  </div>

  {displayViewListContent(#minicart.vertical.childs#)}

</div>

{displayViewListContent(#minicart.vertical.base#)}
