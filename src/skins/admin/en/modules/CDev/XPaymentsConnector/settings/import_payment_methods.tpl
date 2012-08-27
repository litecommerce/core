{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Import payment methods 
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<h3>{t(#Import payment methods#)}</h3>

<widget class="\XLite\Module\CDev\XPaymentsConnector\View\Form\ExportPaymentMethods" name="test" />

  <p>{t(#To be able to use the payment methods defined in X-Payments you should import information about them from X-Payments. Click the button below and X-Payments will return a list of payment methods available for this shopping cart.#)}</p>
  <widget class="\XLite\View\Button\Submit" label="{t(#Request payment methods#)}" style="main" />

<widget name="test" end />

<div IF="hasPaymentMethodsList()">

  <p>{t(#X-Payments returned the following payment methods which are available for using with your shopping cart:#)}</p>



<widget class="\XLite\Module\CDev\XPaymentsConnector\View\Form\ImportPaymentMethods" name="export" />

  <table class="data-table">
  <tr>
    <td align="right" colspan="7"><a href="{buildUrl(#module#,#xpc_clear#,_ARRAY_(#moduleId#^module.getModuleID()))}">{t(#Clear#)}</a></td>
  </tr>

  <tr>
    <th>{t(#Payment method#)}</th>
    <th>{t(#X-Payments configuration ID#)}</th>
    <th>{t(#Sale#)}</th>
    <th>{t(#Auth#)}</th>
    <th>{t(#Capture#)}</th>
    <th>{t(#Void#)}</th>
    <th>{t(#Refund#)}</th>
  </tr>

  <tr FOREACH="getPaymentMethodsList(),pm">
    <td>{pm.name}</td>
    <td>{pm.id}</td>
    <td>{getTransactionTypeStatus(pm,#sale#)}</td>
    <td>{getTransactionTypeStatus(pm,#auth#)}</td>
    <td>{getTransactionTypeStatus(pm,#capture#)}</td>
    <td>{getTransactionTypeStatus(pm,#void#)}</td>
    <td>{getTransactionTypeStatus(pm,#refund#)}</td>
  </tr>

  </table>

  <widget class="\XLite\View\Button\Submit" label="{t(#Import payment methods#)}" style="main" />

<widget name="export" end />

  <div IF="hasPaymentMethodsList()">

  <p IF="isPaymentMethodsImported()">{t(#Warning! Payment methods have already been imported from X-Payments early. All of them will be removed from the database if you will select to import payment methods again.#)}</p>

  </div>

</div>
