{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order notes
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="order.note", weight="100")
 *}

<div class="admin-note">
  <widget class="\XLite\View\FormField\Textarea\Simple" label="Order note" fieldName="adminNotes" value="{order.getAdminNotes()}" />
</div>
