{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *
 * @ListChild (list="product.inventory.parts", weight="10")
 *}

<tr>
  <td>{t(#Inventory tracking for this product is#)}</td>
  <td>
    <select name="{getNamePostedData(#enabled#)}">
      <option value="1" selected="{inventory.getEnabled()=#1#}">{t(#Enabled#)}</option>
      <option value="0" selected="{inventory.getEnabled()=#0#}">{t(#Disabled#)}</option>
    </select>
  </td>
</tr>
