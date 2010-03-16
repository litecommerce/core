{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shopping cart button
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="or-use">or use</div>
<div IF="googleAllowPay" class="gcheckout-button"><a href="{buildUrl(#googlecheckout#,#checkout"#)}"><img src="{googleCheckoutButtonUrl}" width="160" height="43" border="0" alt="" /></a></div>
<div IF="!googleAllowPay" class="gcheckout-button gcheckout-button-disabled"><img src="{googleCheckoutButtonUrl}" width="160" height="43" border="0" alt="" /></div>
