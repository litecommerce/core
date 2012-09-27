{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product element
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="product.modify.list", weight="1020")
 *}
<tr>
  <td class="name-attribute">{t(#Meta keywords#)}</td>
  <td class="star"></td>
  <td class="value-attribute"><input type="text" name="{getNamePostedData(#meta_tags#)}" value="{product.meta_tags}" size="50" /></td>
</tr>
