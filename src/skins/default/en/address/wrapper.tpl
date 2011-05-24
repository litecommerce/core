{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<div class="address-box">

  <table width="100%" IF="{address.getAddressId()}">

    <tr>

      <td class="address-text">

        <widget template="address/text/body.tpl" />

        <div class="delete-action">
          <widget class="\XLite\View\Button\DeleteAddress" addressId="{address.getAddressId()}" />
        </div>

        <div class="clear"></div>

     </td>

    </tr>

    <tr>

      <td class="address-entry-actions-cell">

        <div class="change-action">
          <widget class="\XLite\View\Button\ModifyAddress" label="Change" addressId="{address.getAddressId()}" />
        </div>

        <div class="address-icons">
          <img src="images/icon_billing.png" title="Billing address" class="address-type-icon" IF="{address.getIsBilling()}" alt="" />
          <img src="images/icon_shipping.png" title="Shipping address" class="address-type-icon" IF="{address.getIsBilling()}" alt="" />
        </div>

        <div class="clear"></div>

      </td>

    </tr>

  </table>

  <div class="address-center-button" IF="{!address.getAddressId()}">
    <widget class="\XLite\View\Button\AddAddress" style="action" label="Add new address" profileId="{profile_id}" />
  </div>

</div>
