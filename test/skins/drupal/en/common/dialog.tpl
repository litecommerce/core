{* Common dialog component *}
<BR>
<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
    <TD height=20 class=DialogTitle>&nbsp;&nbsp;{if:widget.href}<a href="{widget.href}">{end:}<font class=DialogTitle>{widget.head:h}</font>{if:widget.href}</a>{end:}{if:widget.showLocationPath}{foreach:locationPath,cname,curl}&nbsp;&gt;&nbsp;<a href="{curl}"><font class=DialogTitle>{cname:h}</font></a>{end:}{end:}</TD>
</TR>
<TR>
    <TD height=8><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=1 BORDER="0" alt=""></TD>
</TR>
<TR>
    <TD>
        <widget template="{body}">
    </TD>
</TR>
</TABLE>
