{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice bottom block
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="invoice.base", weight="50")
 *}
<table cellspacing="0" width="100%">

  <tr FOREACH="getViewList(#invoice.bottom#),w">
    {w.display()}
  </tr>

</table>
