{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Orders search Order ID condition
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="orders.search.conditions", weight="30")
 *}
<tr class="status">
  <td class="title">{t(#Status#)}:</td>
  <td height="10">
    <widget class="\XLite\View\StatusSelect" field="status" value="{getCondition(#status#)}" allOption />
  </td>
  <td>&nbsp;</td>
</tr>
