{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Group title
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.14
 *
 * @ListChild (list="attributes.book.row.group", weight="200")
 *}

<div class="group-title attributes-entry-element">
  <div class="open-group"></div>
  <span class="title">{getGroupTitle():h}</span>
  <input type="text" name="{getNamePostedData(#title#)}" value="{getGroupTitle():h}" />
</div>
