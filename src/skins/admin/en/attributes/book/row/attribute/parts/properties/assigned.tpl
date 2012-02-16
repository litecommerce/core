{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Attribute assigned info
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.14
 *
 * @ListChild (list="attributes.book.row.attribute.properties", weight="400")
 *}

<tr IF="!isNew()">
  <td colspan="3">

    <ul class="assigned-product-classes">

      <li class="assigned-label">{t(#Assigned to#)}</li>

      <li class="popup">
        <widget class="\XLite\View\Button\Attribute\AssignClasses" attribute="{getAttribute()}" />
      </li>

      <li IF="getAssignedProductsCount()" class="products-info-label">({getAssignedProductsInfoLabel()})</li>

    </ul>

  </td>
</tr>
