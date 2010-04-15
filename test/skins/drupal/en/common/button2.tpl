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
<TABLE border="0" cellpadding="0" cellspacing="0" class="CommonButton2LeftBG">
<TR>
<TD><IMG src="images/spacer.gif" width="1" height="17" border="0" alt=""></TD>
<TD valign="top"  nowrap>&nbsp;&nbsp;<a href="{href:t}" target="{hrefTarget}" class="ButtonLink">{label:h}</a>&nbsp;&nbsp;</TD>
<TD IF="img"><IMG src="images/{img}" border="0" align="middle" alt="{label:h}"></TD>
{if:type=#logoff#}
<td><img src="images/spacer.gif" width="9" height="1" border="0" alt="" ></td>
{end:}
<TD><IMG src="images/btn2_right.gif" width="3" height="17" border="0" alt=""></TD>
</TR>
</TABLE>
