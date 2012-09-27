{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<div class="address-box">

  <table width="100%" IF="{address.getAddressId()}">

    <tr>

      <td class="address-text">
        <widget template="address/text/body.tpl" />
      </td>

      <td valign="top" align="center">
        <widget class="\XLite\View\Button\DeleteAddress"  addressId="{address.getAddressId()}" />
      </td>

    </tr>

    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>

    <tr>

      <td>
        <widget class="\XLite\View\Button\ModifyAddress" label="{t(#Change#)}" addressId="{address.getAddressId()}" />
      </td>

      <td align="center">
        <img src="images/icon_billing.png" title="{t(#This address was used as a billing address during the recent purchase#)}" class="address-type-icon" IF="{address.getIsBilling()}" alt="" />
        <img src="images/icon_shipping.png" title="{t(#This address was used as a shipping address during the recent purchase#)}" class="address-type-icon" IF="{address.getIsShipping()}" alt="" />
      </td>

    </tr>

  </table>

  <div class="address-center-button" IF="{!address.getAddressId()}">
    <widget class="\XLite\View\Button\AddAddress" label="{t(#Add new address#)}" style="main-button" profileId="{profile_id}" />
  </div>

</div>
