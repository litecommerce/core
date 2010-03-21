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

<p IF="cart.maxOrderAmountError">
In order to perform checkout your order subtotal must be less than {price_format(config.General.maximal_order_amount):h}
</p>
<p IF="cart.minOrderAmountError">
In order to perform checkout your order subtotal must be more than {price_format(config.General.minimal_order_amount):h}
</p>

<div><widget class="XLite_View_Button_GoBack" /></div>

