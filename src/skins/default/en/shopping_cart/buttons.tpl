{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<br />
<table width="100%">
  <tr>

    <td align="left" nowrap {if:xlite.GoogleCheckoutEnabled}valign="top"{end:}>
	  <widget class="XLite_View_Button_Regular" label="Clear cart" action="clear" />
    </td>

    <td width=20>&nbsp;</td>

    <td align="left" nowrap {if:xlite.GoogleCheckoutEnabled}valign="top"{end:}>
	  <widget class="XLite_View_Button_Submit" label="Update cart" />
    </td>

    <td width="100%">&nbsp;</td>

    <td align="right" nowrap>
	  <widget class="XLite_View_Button_Link" label="Continue shopping" location="{session.continueURL}" />
    </td>

    <td width=20>&nbsp;</td>

    <td align="right" nowrap {if:xlite.GoogleCheckoutEnabled}valign="top"{end:}>
	  <widget class="XLite_View_Button_Regular" label="Checkout" action="checkout" />
    </td>

  </tr>
</table>
