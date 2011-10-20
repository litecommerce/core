{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="product.inventory.parts", weight="30")
 *}

<tr>
  <td>{t(#Low limit notification for this product is#)}</td>
  <td>
    <select name="{getNamePostedData(#lowLimitEnabled#)}">
      <option value="1" selected="{inventory.getLowLimitEnabled()=#1#}">{t(#Enabled#)}</option>
      <option value="0" selected="{inventory.getLowLimitEnabled()=#0#}">{t(#Disabled#)}</option>
    </select>
  </td>
</tr>
