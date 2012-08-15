{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order : line 3
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="order.operations", weight="400")
 *}

<div IF="isCustomerNotesVisible()" class="line-4">
  <h2>{t(#Customer note#)}</h2>
  <list name="order.customerNotes" />
</div>
