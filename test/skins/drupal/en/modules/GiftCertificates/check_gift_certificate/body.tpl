{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Verify gift certificate
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<h3 class="verify-gc">Verify gift certificate</h3>

<widget class="XLite_Module_GiftCertificates_View_Form_GiftCertificate_Check" name="check_form" className="verify-gc" />

  <img src="images/spacer.gif" alt="" />
  <input type="text" name="gcid" value="{getGcIdValue()}"{if:!gcid} class="temporary"{end:} />
  <widget class="XLite_View_Button_Submit" label="Verify" />

<script type="text/javascript">
<!--
$(document).ready(
  function() {
    $('form.verify-gc').submit(
      function() {
        return 0 == $('input.temporary', this).length;
      }
    );

    $('form.verify-gc input.temporary').focus(
      function() {
        if (!this.temporaryValue) {
          this.temporaryValue = this.value; 
        }

        if ($(this).hasClass('temporary')) {
          this.value = '';

          $(this).removeClass('temporary');
        }
      }
    ).blur(
      function() {
        if (!this.value && this.temporaryValue) {
          this.value = this.temporaryValue;
          $(this).addClass('temporary');
        }
      }
    );

  }
);
-->
</script>

<widget name="check_form" end />

{if:gcid&isFound()}
  <table cellspacing="0" class="form-table gc-info">

    <tr>
      <td class="label">To:</td>
      <td class="strong">{foundgc.recipient:h}</td>
    </tr>

    <tr>
      <td class="label">From:</td>
      <td class="strong">{foundgc.purchaser:h}</td>
    </tr>

    <tr class="amount">
      <td class="label">Amount:</td>
      <td>
        <span class="strong">{price_format(foundgc,#debit#):h}</span>
        <span class="gc-comment">(initial amount {price_format(foundgc,#amount#):h})</span>
      </td>
    </tr>

    <tr>
      <td class="label">Number:</td>
      <td>{foundgc.gcid:h}</td>
    </tr>

    <tr>
      <td class="label">Status:</td>
      <td>{getStatus()}</td>
    </tr>

  </table>

  {if:foundgc.status=#A#}
    <widget class="XLite_Module_GiftCertificates_View_Form_GiftCertificate_Apply" name="apply_form" className="apply-gc" />
      <widget class="XLite_View_Button_Submit" label="Redeem certificate" />
    <widget name="apply_form" end />
  {end:}

{else:}

  <strong IF="gcid">Gift certificate is not found</strong>

{end:}
