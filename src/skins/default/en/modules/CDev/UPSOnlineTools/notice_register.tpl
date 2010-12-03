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
<tbody IF="{config.UPSOnlineTools.av_status=#Y#}">

<tr><td colspan="4">
<TABLE border="0">
<TR>
<TD colspan="2">
    <B>Note:</B> Shipping address will be validated by UPS OnLine&reg; Tools Address Validation (US customers only)
    <BR><BR>
</TD>
</TR>

<TR>
<TD  colspan="2" align="center" class="TableHead">
<FONT class="SmallText">NOTICE: The address validation functionallity will validate P.O. Box addresses, however, UPS does not deliver to P.O. boxes, attempts by customer to ship to a P.O. Box via UPS may result in additional charges.</FONT>
</TD>
</TR>
</TABLE>

<widget template="modules/CDev/UPSOnlineTools/bottom.tpl">
<hr>

</td></tr>
</tbody>
