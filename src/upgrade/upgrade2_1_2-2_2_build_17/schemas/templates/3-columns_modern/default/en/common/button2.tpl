{* Dialog button *}
<TABLE border="0" cellpadding="0" cellspacing="0" class="CommonButton2LeftBG">
<TR>
<TD><IMG src="images/spacer.gif" width="1" height="17" border="0" alt=""></TD>
<TD valign="top"  nowrap>&nbsp;&nbsp;<a href="{widget.href}" target="{widget.hrefTarget}" class="ButtonLink">{widget.label:h}</a>&nbsp;&nbsp;</TD>
<TD IF="widget.img"><IMG src="images/{widget.img}" border="0" align="middle" alt="{widget.label:h}"></TD>
{if:widget.type=#logoff#}
<td><img src="images/spacer.gif" width="9" height="1" border="0" alt="" ></td>
{end:}
<TD><IMG src="images/btn2_right.gif" width="3" height="17" border="0" alt=""></TD>
</TR>
</TABLE>

