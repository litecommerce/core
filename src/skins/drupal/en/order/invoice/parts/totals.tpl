{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice totals
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="invoice.base", weight="40")
 *}
<table cellspacing="0" class="invoice-totals">

  <tr FOREACH="getViewList(#invoice.totals#),w">
    {w.display()}
  </tr>

  <tr class="grand-total">
    <td>{t(#Grand total#)}:</td>
    <td class="total">{order.getTotal():p}</td>
  </tr>

</table>
