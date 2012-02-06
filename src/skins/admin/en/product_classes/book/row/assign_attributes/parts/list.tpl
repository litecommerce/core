{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Attributes list
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.16
 *
 * @ListChild (list="productClass.book.row.assignAttributes", weight="100")
 *}

<ul FOREACH="getGroupedAttributes(),data" class="attributes-list-popup">
  <li IF="getObjectField(getArrayField(data,#group#),#isPersistent#)">
    {displayNestedViewListContent(#group#,_ARRAY_(#group#^getArrayField(data,#group#)))}
  </li>

  <li FOREACH="getArrayField(data,#attributes#),attribute">
    {displayNestedViewListContent(#attribute#,_ARRAY_(#attribute#^attribute))}
  </li>
</ul>
