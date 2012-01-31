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
  <td colspan="10">
    <span>{t(#Assigned to#)}</span>
    <widget class="\XLite\View\Button\Popup\Link" label="{getAssignClassesLinkTitle():h}" popupTarget="attribute_assign_classes" popupWidget="\XLite\View\Attributes\Book\Row\Attribute\AssignClasses" />
    <span IF="getAssignedProductsCount()">{getAssignedProductsInfoLabel()}</span>
  </td>
</tr>
