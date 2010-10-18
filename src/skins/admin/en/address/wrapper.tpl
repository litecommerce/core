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

<ul class="address-box">

  <table width="100%" IF="{address}">

    <tr>
      <td class="address-text" width="100%">

        <widget template="address/text/body.tpl" />

      </td>

      <td valign="top" align="center">
        <img src="images/icon_billing.png" title="Billing address" class="address-type-icon" IF="{address.getIsBilling()}" />
        <img src="images/icon_shipping.png" title="Shipping address" class="address-type-icon" IF="{address.getIsBilling()}" />
      </td>
    </tr>

    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>

    <tr>
      <td>
        <widget class="\XLite\View\Button\Regular" label="Change" jsCode="openModifyAddress(this, '{address.getAddressId()}');" />
      </td>

      <td align="center">
        <widget class="\XLite\View\Button\Link" label="Delete" jsCode="openDeleteAddress(this, '{address.getAddressId()}');" style="button delete-address" />
      </td>
    </tr>

  </table>

  <div class="address-center-button" IF="{!address}">
    <widget class="\XLite\View\Button\Regular" label="Add new address" jsCode="openAddAddress(this, '{profile_id}');" />
  </div>

</ul>

