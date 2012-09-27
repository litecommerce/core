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

{if:hasPaymentModules()}

  <div IF="hasGateways()" class="box gateways">
    <h2>{t(#Accepting credit card online#)}</h2>
    <div class="content">

      {if:hasAddedGateways()}

        <widget class="XLite\View\ItemsList\Payment\Method\Admin\Gateways" />
        <widget
          class="XLite\View\Button\Payment\AddMethod"
          paymentType={%\XLite\Model\Payment\Method::TYPE_ALLINONE%} style="add-method" />
        <div IF="countNonAddedGateways()" class="counter">{t(#X methods available#,_ARRAY_(#count#^countNonAddedGateways()))}</div>

      {else:}

        <p>{t(#Use a merchant account from your financial institution or choose a bundled payment solution to accept credit cards and other methods of payment on your website.#)}</p>
        <widget
          class="XLite\View\Button\Payment\AddMethod"
          paymentType={%\XLite\Model\Payment\Method::TYPE_ALLINONE%} style="action" />

      {end:}

    </div>
  </div>

  <div IF="hasAlternative()" class="box alternative">
    <h2>{t(#Alternative payment methods#)}</h2>
    <div class="content">

      {if:hasAddedAlternative()}

        <widget class="XLite\View\ItemsList\Payment\Method\Admin\Alternative" />
        <widget
          class="XLite\View\Button\Payment\AddMethod"
          paymentType={%\XLite\Model\Payment\Method::TYPE_ALTERNATIVE%} style="add-method" />
        <div IF="countNonAddedAlternative()" class="counter">{t(#X methods available#,_ARRAY_(#count#^countNonAddedAlternative()))}</div>

      {else:}

        <p>{t(#Give buyers a way to pay by adding an alternative payment method.#)}</p>
        <widget
          class="XLite\View\Button\Payment\AddMethod"
          paymentType={%\XLite\Model\Payment\Method::TYPE_ALTERNATIVE%} style="action"/>

      {end:}

    </div>
  </div>

  <div class="subbox marketplace">
    <h2>{t(#Need more payment methods?#)}</h2>
    <p>{t(#In order to accept credit cards payments you should install the neccessary payment module from our Marketplace.#)}</p>
    <widget class="XLite\View\Button\Link" label="{t(#Go to Marketplace#)}" location="{buildURL(#addons_list_marketplace#)}" />
  </div>

{else:}

  <div class="box no-payment-modules">
    <h2>{t(#No payment modules installed#)}</h2>
    <div class="content">
      <p>{t(#In order to accept credit cards payments you should install the neccessary payment module from our Marketplace.#)}</p>
      <widget class="XLite\View\Button\Link" label="{t(#Go to Marketplace#)}" location="{buildURL(#addons_list_marketplace#)}" style="action" />
    </div>
  </div>

{end:}

<div class="subbox watch-video">
  <h2>{t(#Understanding Online Payments#)}</h2>
  <p>{t(#Watch this short video and learn the basics of how online payment processing works#)}</p>
  <widget class="XLite\View\Button\Link" label="{t(#Watch video#)}" location="{getVideoURL()}" style="watch-video" />
</div>

<div class="box offline-methods">
  <h2>{t(#Offline methods#)}</h2>
  <div class="content">
    <widget class="XLite\View\ItemsList\Payment\Method\Admin\OfflineModules" />
    <widget class="XLite\View\ItemsList\Payment\Method\Admin\Offline" />
    <widget
      class="XLite\View\Button\Payment\AddMethod"
      paymentType={%\XLite\Model\Payment\Method::TYPE_OFFLINE%} style="add-method" />
  </div>
</div>

</div>
