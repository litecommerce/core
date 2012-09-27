{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules main section list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.module.install.columns.module-main-section", weight="200")
 *}

<form action="{getPurchaseURL(module)}" method="get" IF="canPurchase(module)">
  <input type="hidden" name="q" value="pay" />
  <input type="hidden" name="name" value="{module.getName()}" />
  <input type="hidden" name="author" value="{module.getAuthor()}" />
  <input type="hidden" name="return_url" value="{getReturnURL()}" />
  <input type="hidden" name="email" value="{auth.profile.getLogin()}" />

  <div class="purchase">
    <widget class="\XLite\View\Button\Submit" label="{t(#Purchase#)}" />
  </div>
</form>
