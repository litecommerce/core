{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product element
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="product.modify.list", weight="500")
 *}

<tr>
  <td class="name-attribute">{t(#Price#)}</td>
  <td class="star">&nbsp;*&nbsp;</td>
  <td class="value-attribute">
    <input type="text" name="{getNamePostedData(#price#)}" size="18" value="{product.price}" />
  </td>
</tr>
