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

<div class="address-dialog">

  <h2>Delete address</h2>

  <h4>You have selected to delete this address:</h4>

  <div class="address-plain">
    <widget class="\XLite\View\Address" displayMode="text" displayWapper="" address="{address}" />
  </div>

  <h4>Please, confirm you want to proceed:</h4>

  <form action="admin.php" method="post" name="delete_address_form">
    <input type="hidden" name="target" value="address_book" />
    <input type="hidden" name="action" value="delete" />
    <input type="hidden" name="address_id" value="{address.getAddressId()}" />

    <div class="button">
      <widget class="\XLite\View\Button\Submit" label="Proceed" style="button-proceed" />

      <widget class="\XLite\View\Button\Regular" label="Cancel" jsCode="document.forms['delete_address_form'].elements['action'].value='cancel_delete'; document.forms['delete_address_form'].submit();" style="button-cancel" />
    </div>

  </form>

</div>

