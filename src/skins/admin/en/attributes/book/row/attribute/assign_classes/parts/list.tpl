{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Attribute classes list
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.16
 *
 * @ListChild (list="attributes.book.row.assignClasses", weight="100")
 *}

<ul class="classes-list-popup">
  <li FOREACH="getProductClasses(),class">
    <input type="checkbox" name="{getNamePostedData(class.getId())}" id="class_{class.getId()}" value="1" checked="{isProductClassAssigned(class)}" />
    <label for="class_{class.getId()}">{class.getName()}</label>
  </li>
</ul>
