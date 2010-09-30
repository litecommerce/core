{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product details attributes block
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *
 * @ListChild (list="product.details.page.tabs", weight="10")
 *}
<ul IF="product.getExtraFields(true)|product.weight|isViewListVisible(#product.details.common.product-attributes#)" class="extra-fields">

  <li IF="!product.weight=0">
    <strong>{t(#Weight#)}:</strong>
    <span>{product.weight} {config.General.weight_symbol}</span>
  </li>

  <widget class="\XLite\View\ExtraFields" product="{product}" />

  {displayViewListContent(#product.details.common.product-attributes#)}

</ul>
