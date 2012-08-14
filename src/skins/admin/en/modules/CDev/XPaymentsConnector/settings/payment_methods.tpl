{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Payment methods 
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<h3>{t(#Payment methods#)}</h3>

<widget class="\XLite\Module\CDev\XPaymentsConnector\View\Form\PaymentMethods" name="methods" />

  <p>{t(#The list below represented the payment methods imported from X-Payments and selected as a payment options available for customer on the checkout process.#)}</p>

  <table class="data-table">

  <tr>
    <th>{t(#Payment method#)}</th>
    <th>{t(#X-Payments configuration ID#)}</th>
    <th>{t(#Sale#)}</th>
    <th>{t(#Auth#)}</th>
    <th>{t(#Capture#)}</th>
    <th>{t(#Void#)}</th>
    <th>{t(#Refund#)}</th>
    <th>{t(#Use lite interface#)}</th>
  </tr>

  <tr FOREACH="getPaymentMethods(),pm">
    <td>{pm.getSetting(#name#)}</td>
    <td>{pm.getSetting(#id#)}</td>
    <td>{getTransactionTypeStatus(pm,#sale#)}</td>
    <td>{getTransactionTypeStatus(pm,#auth#)}</td>
    <td>{getTransactionTypeStatus(pm,#capture#)}</td>
    <td>{getTransactionTypeStatus(pm,#void#)}</td>
    <td>{getTransactionTypeStatus(pm,#refund#)}</td>
    <td><widget class="\XLite\View\FormField\Select\YesNo" fieldName="list[{pm.method_id}][lite]" fieldOnly=true value="{pm.getSetting(#useLiteInterface#)}" /></td>
  </tr>

  </table>

  <widget class="\XLite\View\Button\Submit" label="{t(#Update interface settings#)}" style="main" />

<widget name="methods" end />

  <p>{t(#Configuring of the payment methods presented possible in the X-Payments backoffice only. In case of any problems you need to review the X-Payments Connector module settings and if it's ok, review payment configurations settings on the X-Payments side.#)}</p>
