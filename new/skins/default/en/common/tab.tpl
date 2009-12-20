{* Tab template *}
<TABLE border="0" cellpadding="0" cellspacing="0" width="95">
<TR IF={target=widget.active}>
	<TD><IMG src="images/tab_selected_left.gif" width="9" height="42" border="0" alt=""></TD>
	<TD style="padding-bottom: 2px;" width="100%" class="TabSelectedBG" nowrap align="center"><A class="TopTabLink" href="{widget.href:h}"><FONT class="SelectedTab">{widget.label:h}</FONT></A></TD>
	<TD><IMG src="images/tab_selected_right.gif" width="9" height="42" border="0" alt=""></TD>
</TR>
<TR IF={!target=widget.active}>
	<TD><IMG src="images/tab_left.gif" width="9" height="42" border="0" alt=""></TD>
	<TD style="padding-bottom: 2px;" width="100%" class="TabNormalBG" nowrap align="center"><A class="TopTabLink" href="{widget.href:h}">{widget.label:h}</A></TD>
	<TD><IMG src="images/tab_right.gif" width="9" height="42" border="0" alt=""></TD>
</TR>
</TABLE>
