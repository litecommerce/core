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

<widget template="common/dialog.tpl" head="Order processed" body="checkout/success_message.tpl" />

<widget class="XLite_View_Button_Link" label="Continue shopping" location="{buildURL()}" />
<widget class="XLite_View_Button_Link" label="Print invoice" location="{buildUrl(#invoice#,##,_ARRAY_(#order_id#^order.order_id,#printable#^#1#))}" />
<widget template="common/invoice.tpl" />
<widget class="XLite_View_Button_Link" label="Continue shopping" location="{buildURL()}" />

