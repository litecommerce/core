{* Common dialog component *}
<BR>
<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
    <TD height=20 class=DialogTitle>&nbsp;&nbsp;{if:href}<a href="{href}">{end:}<font class=DialogTitle>{head:h}</font>{if:href}</a>{end:}{if:showLocationPath}{foreach:locationPath,cname,curl}&nbsp;&gt;&nbsp;<a href="{curl}"><font class=DialogTitle>{cname:h}</font></a>{end:}{end:}</TD>
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
