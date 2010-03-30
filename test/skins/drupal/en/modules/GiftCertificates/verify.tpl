{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Verify box
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
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
