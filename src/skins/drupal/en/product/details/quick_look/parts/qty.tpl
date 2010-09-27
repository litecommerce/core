{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Quantity input box
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *
 * @ListChild (list="quickLook.buttons", weight="5")
 * @ListChild (list="quickLook.buttons.added", weight="5")
 *}

{t(#Qty#)}: <input type="text" value="{product.getMinPurchaseLimit()}" class="quantity field-requred field-integer field-positive field-non-zero" />
