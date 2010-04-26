{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Payment module configuration
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<script type="text/javascript">
<!--
function showSettings(paypal) 
{
  if (paypal == "standard") {

    document.getElementById('standard').style.display = '';
    document.getElementById('standard_notes').style.display = '';
    document.getElementById('pro').style.display = 'none';

  } else {

    document.getElementById('standard').style.display = 'none';
    document.getElementById('standard_notes').style.display = 'none';
    document.getElementById('pro').style.display = '';

  }
}

function showStdServUrl(mode) 
{
  if (mode == "1") {

    document.getElementById('std_live_url').style.display = '';
    document.getElementById('std_test_url').style.display = 'none';

  } else {

    document.getElementById('std_live_url').style.display = 'none';
    document.getElementById('std_test_url').style.display = '';

  }
}
-->
</script>

<form action="admin.php" method="POST">
  <input type="hidden" name="target" value="payment_method">
  <input type="hidden" name="action" value="update">
  <input type="hidden" name="payment_method" value="paypalpro">

  <table width="100%" cellpadding="4" cellspacing="0">

    <tr>
      <td colspan="3" class="DialogTitle">Select a PayPal Solution:</td>
    </tr>

    <tr>
      <td align="center" rowspan="6">
        <a href="#" onclick="javascript:window.open('https://www.paypal.com/cgi-bin/webscr?cmd=xpt/popup/OLCWhatIsPayPal-outside','olcwhatispaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no', width='400', height='350');"><img  src="https://www.paypal.com/en_US/i/logo/PayPal_mark_50x34.gif" border="0" alt="Acceptance Mark"></a>
      </td>
      <td align="center">
        <input type="radio" is="solution_s" name="params[solution]" value="standard" onclick="javascript: this.blur(); showSettings('standard')" checked="{isSelected(#standard#,pm.params.solution)}" />
      </td>
      <td><label for="solution_s"><strong>Website Payments Standard</strong></label></td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>
        Customers shop on your website and pay on Paypal.&nbsp;<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_wp-standard-overview-outside"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle">Solution overview</a>
      </td>
    </tr>

    <tr>
      <td align="center">
        <input type="radio" id="solution_p" name="params[solution]" value="pro" onclick="javascript: this.blur(); showSettings('pro')" checked="{isSelected(#pro#,pm.params.solution)}" />
      </td>
      <td><label for="solution_p"><strong>Website Payments Pro</strong></label></td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>
        Customers shop and pay on your website.&nbsp;<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_wp-pro-overview-outside"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle">Solution overview</a><br />
        At present PayPal Website Payments Pro is only available to US merchants.
      </td>
    </tr>

    <tr>
      <td align="center">
        <input type="radio" id="solution_e" name="params[solution]" value="express" onclick="javascript: this.blur(); showSettings('express')" checked="{isSelected(#express#,pm.params.solution)}" />
      </td>
      <td><label for="solution_e"><strong>Express Checkout</strong></label></td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>
        Add PayPal as an additional payment option to increase sales.&nbsp;<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_additional-payment-ref-impl1"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle">Solution overview</a><br />
        At present PayPal Express Checkout is only available to US merchants.
      </td>
    </tr>

  </table>  

  <hr />

  <table id="standard" cellpadding="3">

    <tr>
      <td colspan="2" class="DialogTitle">Website Payments Standard settings:</td>
    </tr>

    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>

    <tr>
        <td width="250px">Account e-mail:</td>
        <td><input type="text" size="30" name="params[standard][login]" value="{pm.params.standard.login:h}"></td>
    </tr>

    <tr>
        <td>Logo URL:</td>
        <td><input type="text" size="60" name="params[standard][logo]" value="{pm.params.standard.logo:h}"></td>
    </tr>

    <tr>
        <td>Order prefix: </td>
        <td><input type="text" size="10" name="params[standard][prefix]" value="{pm.params.standard.prefix:h}"></td>
    </tr>

    <tr>
      <td>Currency:</td>
      <td>
        <select class="FixedSelect" name="params[standard][currency]">
          <option value="USD" selected="{isSelected(#USD#,pm.params.standard.currency)}">U.S. Dollars (USD)</option>
          <option value="CAD" selected="{isSelected(#CAD#,pm.params.standard.currency)}">Canadian Dollars (CAD)</option>
          <option value="EUR" selected="{isSelected(#EUR#,pm.params.standard.currency)}">Euros (EUR)</option>
          <option value="GBP" selected="{isSelected(#GBP#,pm.params.standard.currency)}">Pounds Sterling (GBP)</option>
          <option value="JPY" selected="{isSelected(#JPY#,pm.params.standard.currency)}">Yen (JPY)</option>
          <option value="AUD" selected="{isSelected(#AUD#,pm.params.standard.currency)}">Australian Dollars (AUD)</option>
        </select>
      </td>
    </tr>

    <tr>
      <td>Transaction Mode:</td>
      <td>
        <select class="FixedSelect" name="params[standard][mode]" onChange="showStdServUrl(this.value)">
          <option value="1" selected="{isSelected(#1#,pm.params.standard.mode)}">Live</option>
          <option value="0" selected="{isSelected(#0#,pm.params.standard.mode)}">Test</option>
        </select>  
      </td>
    </tr>

    <tr>
      <td>Service URL:</td>
      <td>
        <span id="std_live_url" style="display:;"><input type="text" size="60" name="params[standard][live_url]" {if:isEmpty(pm.params.standard.live_url)} value="https://www.paypal.com/cgi-bin/webscr" {else:} value="{pm.params.standard.live_url:h}"{end:}></span>
        <span id="std_test_url" style="display: none;"><input type="text" size="60" name="params[standard][test_url]" {if:isEmpty(pm.params.standard.test_url)} value="https://www.sandbox.paypal.com/cgi-bin/webscr" {else:} value="{pm.params.standard.test_url:h}"{end:}></span>
      </td>
    </tr>

    <tr>
      <td>Treat PayPal orders in progress<br> as 'Queued' rather than 'Incomplete':</td>
      <td>
        <input type="checkbox" name="params[standard][use_queued]" value="1" onclick="this.blur()" checked="{pm.params.standard.use_queued}">
      </td>
    </tr>

  </table>

<script type="text/javascript">
showStdServUrl('{pm.params.standard.mode:h}');
</script>

  <table id="pro" style="display: none;" cellpadding="3">

    <tr>
      <td colspan="2" class="DialogTitle">Website Payments Pro and Express Checkout settings:</td>
    </tr>

    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>

    <tr> 
      <td width="250px">Login:</td>
      <td><input type="text" size="30" name="params[pro][login]" value="{pm.params.pro.login:h}"></td>
    </tr>

    <tr> 
      <td>Password:</td>
      <td><input type="text" size="30" name="params[pro][password]" value="{pm.params.pro.password:h}"></td>
    </tr>

    <tr>
      <td>Use PayPal authentication method:</td>
      <td>
        <input type="radio" name="params[pro][auth_method]" value="S" id="auth_method_S" checked="{!isSelected(pm.params.pro.auth_method,#C#)}" /><label for="auth_method_S">API signature</label><br />
        <input type="radio" name="params[pro][auth_method]" value="C" id="auth_method_C" checked="{isSelected(pm.params.pro.auth_method,#C#)}" /><label for="auth_method_C">Ceritifcate</label>
      </td>
    </tr>

    <tr>
      <td>Signature:</td>
      <td><input type="text" size="30" name="params[pro][signature]" value="{pm.params.pro.signature:h}"></td>
    </tr>

    <tr> 
      <td>Certificate filename:</td>
      <td><input type="text" size="30" name="params[pro][certificate]" value="{pm.params.pro.certificate:h}"></td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td><i>For example: /u/john/certs/paypal_certificate.cert</i></td>
    </tr>

    <tr>
      <td>Order prefix: </td>
      <td><input type="text" size="10" name="params[pro][prefix]" value="{pm.params.pro.prefix:h}"></td>
    </tr>

    <tr>
      <td>Currency:</td>
      <td>
        <select class="FixedSelect" name="params[pro][currency]">
          <option value="USD" selected="{isSelected(#USD#,pm.params.pro.currency)}">U.S. Dollars (USD)</option>
          <option value="CAD" selected="{isSelected(#CAD#,pm.params.pro.currency)}">Canadian Dollars (CAD)</option>
          <option value="EUR" selected="{isSelected(#EUR#,pm.params.pro.currency)}">Euros (EUR)</option>
          <option value="GBP" selected="{isSelected(#GBP#,pm.params.pro.currency)}">Pounds Sterling (GBP)</option>
          <option value="JPY" selected="{isSelected(#JPY#,pm.params.pro.currency)}">Yen (JPY)</option>
          <option value="AUD" selected="{isSelected(#AUD#,pm.params.pro.currency)}">Australian Dollars (AUD)</option>
        </select>
      </td>
    </tr>

    <tr>
      <td>Transaction Type:</td>
      <td>
        <select class="FixedSelect" name="params[pro][type]">
          <option value="1" selected="{isSelected(#1#,pm.params.pro.type)}">Sale</option>
          <option value="0" selected="{isSelected(#0#,pm.params.pro.type)}">Authorization</option>
        </select>
      </td>
    </tr>

    <tr>
      <td>Transaction Mode:</td>
      <td>
        <select class="FixedSelect" name="params[pro][mode]">
          <option value="1" selected="{isSelected(#1#,pm.params.pro.mode)}">Live</option>
          <option value="0" selected="{isSelected(#0#,pm.params.pro.mode)}">Test</option>
        </select>
      </td>
    </tr>

  </table>

  <input type="submit" value=" Update " />

</form>

<span id="standard_notes">
  <hr />
  <strong>Note:</strong> Please make sure the 'Instant Payment Notification Preferences' have been set up correctly.<br />
  Log into your PayPal account, go to the "My Account / Profile / Instant Payment Notification Preferences".<br />
  You should have:<br />
  &nbsp;&nbsp;&nbsp;- Instant Payment Notification (IPN) checkbox selected,<br />
  &nbsp;&nbsp;&nbsp;- Instant Payment Notification (IPN) URL field value: <strong>{getShopUrl(#cart.php#)}</strong> .<br />
</span>

<script type="text/javascript">
showSettings('{pm.params.solution:h}');
</script>
