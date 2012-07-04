{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order invoice tab
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.24
 *}

<div class="order-invoice">
  <div class="print"><a href="javascript:void(0);" onclick="javascript: window.print(); return false;">{t(#Print invoice#)}</a></div>
  <widget template="order/invoice/page.tpl" />
</div>
