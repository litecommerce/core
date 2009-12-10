<?php
    $find_str = <<<EOT
<BR>
<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
    <TD height=20 class=DialogTitle>&nbsp;&nbsp;{if:widget.href}<a href="{widget.href:h}">{end:}<font class=DialogTitle>{widget.head}</font>{if:widget.href}</a>{end:}{if:widget.showLocationPath}{foreach:locationPath,cname,curl}&nbsp;&gt;&nbsp;<a href="{curl:r}"><font class=DialogTitle>{cname}</font></a>{end:}{end:}</TD>
</TR>
<TR>
    <TD class=DialogBorder><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=1 BORDER="0"></TD>
EOT;
    $replace_str = <<<EOT
<BR>
<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
    <TD height=20 class=DialogTitle>&nbsp;&nbsp;{if:widget.href}<a href="{widget.href:h}">{end:}<font class=DialogTitle>{widget.head:h}</font>{if:widget.href}</a>{end:}{if:widget.showLocationPath}{foreach:locationPath,cname,curl}&nbsp;&gt;&nbsp;<a href="{curl:r}"><font class=DialogTitle>{cname:h}</font></a>{end:}{end:}</TD>
</TR>
<TR>
    <TD class=DialogBorder><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=1 BORDER="0"></TD>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

	$source = strReplace("</BR>", "", $source, __FILE__, __LINE__);
?>
