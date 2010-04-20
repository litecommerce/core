{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout buttons
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<widget class="XLite_View_Button_Link" label="Checkout" location="{buildUrl(#checkout#)}" style="bright-button big-button checkout-button" />
<widget class="XLite_Module_GoogleCheckout_View_ButtonAltCheckout" module="GoogleCheckout" template="modules/GoogleCheckout/shopping_cart/button.tpl" size="small" background="transparent" />
<widget module="GoogleCheckout" template="modules/GoogleCheckout/shopping_cart/gcheckout_notes.tpl" />
