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

<div class="address-dialog">

  <h2>{t(#Delete address#)}</h2>

  <h4>{t(#You have selected to delete this address#)}:</h4>

  <div class="address-plain">
    <widget class="\XLite\View\Address" displayMode="text" displayWapper="" address="{address}" />
  </div>

  <div class="clear"></div>

  <div class="delete-address-form">

    <h4>{t(#Please, confirm you want to proceed#)}:</h4>
{*TODO: make it through FORM classes*}
    <form action="{buildURL()}" method="post" name="delete_address_form">
      <input type="hidden" name="target" value="address_book" />
      <input type="hidden" name="action" value="delete" />
      <input type="hidden" name="address_id" value="{address.getAddressId()}" />

      <div class="button">
        <widget class="\XLite\View\Button\Submit" label="Proceed" style="button-proceed" />

        <widget class="\XLite\View\Button\Regular" label="Cancel" jsCode="void(0);" style="button-cancel" />
      </div>

    </form>

  </div>

</div>
