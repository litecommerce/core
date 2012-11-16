{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice totals
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="invoice.base", weight="40")
 *}

<div style="width: 40%; float: right; display: block; padding-top: 25px; padding-bottom: 25px;">
<b>{t(#Totals#)}</b>
<table cellspacing="1" cellpadding="3" width="100%">
  <list name="invoice.base.totals" />
</table>
</div>
