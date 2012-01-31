{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Attribute choices list
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.16
 *
 * @ListChild (list="attributes.book.row.addChoices", weight="200")
 *}

<ul id="choices-list">
  <li FOREACH="getChoices(),choice" class="{getChoiceRowCSSClass(choice)}">
    <input type="text" name="{getNamePostedData(#title#,choice.getId())}" value="{choice.getTitle():h}" />
    <div IF="choice.isPersistent()" class="delete choice">
      <input type="hidden" name="{getNamePostedData(#toDelete#,choice.getId())}" value="{#0#}" />
    </div>
  </li>
</ul>
