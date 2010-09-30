{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Add-to-wishlist button
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *
 * @ListChild (list="product.details.page.info.buttons.cart-buttons", weight="30")
 * @ListChild (list="product.details.page.info.buttons-added.cart-buttons", weight="30")
 * @ListChild (list="product.details.quicklook.info.buttons", weight="40")
 *}
<widget class="\XLite\View\Button\Regular" label="Add to Wish list" target="wishlist" action="add" style="bright add2wl" />
