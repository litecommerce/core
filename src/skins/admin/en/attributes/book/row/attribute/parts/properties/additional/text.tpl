{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Properties specific for the "Text" attributes
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.14
 *
 * @ListChild (list="attributes.book.row.attribute.properties", weight="300")
 *}

<tr IF="#Text#=getAttributeTypeName()">
  <td>&nbsp;</td>
  <td>
    <div class="additional-properties label">{t(#Default#)}:</div>
    <div class="additional-properties box">
      <input type="text" name="{getBoxName(#default#)}" value="{getAttributeDefaultValue():h}" />
    </div>
  </td>
</tr>
