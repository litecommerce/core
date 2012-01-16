{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * "Delete group" icon
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.14
 *
 * @ListChild (list="attributes.book.row.group", weight="300")
 *}

<div IF="!isNew()" class="delete group">
  <input type="hidden" name="{getBoxName(#toDelete#)}" value="{#0#}" />
</div>
