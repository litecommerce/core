{* Dialog submit button *}
<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD><IMG SRC="images/button_1.gif" WIDTH=10 HEIGHT=19 BORDER="0"></TD>
<TD background="images/button_bg.gif" nowrap><a href="{widget.href:r}"><INPUT IF="widget.img" TYPE="image" SRC="images/{widget.img}" WIDTH=18 HEIGHT=15 BORDER="0" align="absmiddle" alt="{widget.label:h}"/><INPUT IF="!widget.img" TYPE="image" SRC="images/spacer.gif" WIDTH=1 HEIGHT=1 BORDER=0 align="absmiddle"/>&nbsp;<FONT IF="widget.label" class="Button">{widget.label:h}</FONT>&nbsp;</a></TD>
<TD><IMG SRC="images/button_2.gif" WIDTH=10 HEIGHT=19 BORDER="0"></TD>
</TR>
</TABLE>
