{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Success checkout
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<widget template="checkout/success_message.tpl" />
<br />
<widget class="\XLite\View\Button\Link" label="Continue shopping" location="{buildURL()}" />
&nbsp;&nbsp;
<widget class="\XLite\View\Button\Link" label="Print invoice" location="{buildUrl(#invoice#,##,_ARRAY_(#order_id#^order.order_id,#printable#^#1#))}" />
<br />
<br />
<hr class="tiny" />

<widget template="common/invoice.tpl" />

