{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order invoice additional info
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="admin.invoice.item")
 *}
{if:item.getProductOptions()}
<tr>
  <td colspan="2">Selected options:</td>
</tr>
<tr FOREACH="item.getProductOptions(),option">
  <td colspan="2" style="padding-left: 20px;">{option.getName():h}: {option.getValue():h}</td>
</tr>
{end:}
