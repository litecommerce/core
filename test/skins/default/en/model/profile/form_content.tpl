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
 *}

<table>
  <tr>
    <td width="45%" class="block"><h3 class="title">Billing address</h3></td>
    <td>&nbsp;</td>
    <td width="45%" class="block"><h3 class="title">Shipping address</h3></td>
  </tr>
  <tr>
    <td>
      <table>
        <tr FOREACH="getBillingAddressFields(),field">{field.display()}</tr>
      </table>
    </td>
    <td>&nbsp;</td>
    <td>
      <table>
        <tr FOREACH="getShippingAddressFields(),field">{field.display()}</tr>
      </table>
    </td>
  </tr>
</table>
