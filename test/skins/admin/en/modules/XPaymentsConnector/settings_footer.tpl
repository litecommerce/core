{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Additional settings control
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

{if:isXPCConfigured()}

<br />
<br />

<a name="test_module"></a>

<h3>Test module</h3>

To test module settings and connection with X-Payments click on the button below. If module properly configured, X-Payments response with successful message.

<br />
<br />

<form action="{buildUrl(#module#,#xpc_test#)}" method="post" name="xpc_test_form">
  <input type="hidden" name="page" value="{page}">
  <input type="submit" value="Test module" />
</form>

<br />
<br />
<br />

<a name="export"></a>

<h3>Import payment methods</h3>

To be able use the payment methods defined in X-Payments you should import information about them from X-Payments. Click on the button below and X-Payments return the list of available for this shopping cart payment methods.

<br />
<br />

<form action="{buildUrl(#module#,#xpc_request#)}" method="post" name="xpc_request_pm">
  <input type="hidden" name="page" value="{page}">
  <input type="submit" value="Request payment methods" />
</form>

<div IF="hasPaymentMethodsList()">

<br />

X-Payments returned the following payment methods which are available for using with your shopping cart:

<br />
<br />

<div align="right"><a href="{buildUrl(#module#,#xpc_clear#)}">Clear</a></div>

<table cellpadding="5" cellspacing="1" class="plain-table" width="100%">

  <tr>
    <th>Payment method</th>
    <th>X-Payments configuration ID</th>
    <th>Auth</th>
    <th>Capture</th>
    <th>Void</th>
    <th>Refund</th>
  </tr>

  <tr FOREACH="getPaymentMethodsList(),pm">
    <td>{pm.name}</td>
    <td>{pm.id}</td>
    <td>{if:canTransactionType(pm,#auth#)}Yes{else:}No{end:}</td>
    <td>{if:canTransactionType(pm,#capture#)}Yes{else:}No{end:}</td>
    <td>{if:canTransactionType(pm,#void#)}Yes{else:}No{end:}</td>
    <td>{if:canTransactionType(pm,#refund#)}Yes{else:}No{end:}</td>
  </tr>

</table>

<br />
<br />

<form action="{buildUrl(#module#,#xpc_import#)}" method="post" name="xpc_import">
  <input type="hidden" name="page" value="{page}">
  <input type="submit" value="Import payment methods" />
</form>

<div IF="isPaymentMethodsImported()">Warning! Payment methods have already been imported from X-Payments early. All of them will be removed from the database if you will select to import payment methods again.</div>

</div>

{end:}
