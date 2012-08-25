{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Payment configuration
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<div class="payment-conf">

<div IF="!hasPaymentModules()" class="box no-paument-modules">
  <h2>{t(#No payment modules installed#)}</h2>
  <p>{t(#In order to accept credit cards payments you should install the neccessary payment module from our Marketplace.#)}</p>
  <widget class="XLite\View\Button\Link" label="{t(#Go to Marketplace#)}" location="{buildURL(#addons_list_marketplace#)}" />
</div>

<div class="box offline-methopds">
  <h2>{t(#Offline methods#)}</h2>
  <widget class="XLite\View\ItemsList\Model\Payment\Method\Admin\OfflineModules" />
  <widget class="XLite\View\ItemsList\Model\Payment\Method\Admin\Offline" />
  <widget class="XLite\View\Button\Link" label="{t(#Add payment method#)}" location="{buildURL(#payment_settings#)}" />
</div>

</div>
