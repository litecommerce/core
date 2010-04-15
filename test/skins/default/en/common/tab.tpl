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
<TABLE border="0" cellpadding="0" cellspacing="0" width="95">
<TR IF={target=active}>
	<TD><IMG src="images/tab_selected_left.gif" width="9" height="42" border="0" alt=""></TD>
	<TD style="padding-bottom: 2px;" width="100%" class="TabSelectedBG" nowrap align="center"><A class="TopTabLink" href="{href:h}"><FONT class="SelectedTab">{label:h}</FONT></A></TD>
	<TD><IMG src="images/tab_selected_right.gif" width="9" height="42" border="0" alt=""></TD>
</TR>
<TR IF={!target=active}>
	<TD><IMG src="images/tab_left.gif" width="9" height="42" border="0" alt=""></TD>
	<TD style="padding-bottom: 2px;" width="100%" class="TabNormalBG" nowrap align="center"><A class="TopTabLink" href="{href:h}">{label:h}</A></TD>
	<TD><IMG src="images/tab_right.gif" width="9" height="42" border="0" alt=""></TD>
</TR>
</TABLE>
