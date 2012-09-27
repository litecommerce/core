{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules enter key form
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<div class="enter-addon-key-form">

  <form action="admin.php" method="post" name="getAddonForm" >
    <input type="hidden" name="target" value="module_key" />
    <input type="hidden" name="action" value="register_key" />

    <div class="enter-key-hint">
      {t(#If you have a license key for a commercial module, you can enter it here to register the purchase of the appropriate module.#)}
    </div>

    <div class="addon-key">
      <input type="text" name="key" value="" />
    </div>

    <widget class="\XLite\View\Button\Submit" label="{t(#Validate key#)}" />

    <div class="clear"></div>

  </form>

</div>
