{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order info
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<br /><widget class="XLite_View_Button_NewTab" label="Print invoice" location="{buildURL(#order#,##,_ARRAY_(#mode#^#invoice#,#order_id#^order.order_id))}" />
<p /><widget template="common/invoice.tpl" />
