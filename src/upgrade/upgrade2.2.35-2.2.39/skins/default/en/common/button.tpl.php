<?php

	$find_str = <<<EOT
<TD IF="widget.img" class="CommonButtonBG" nowrap><a href="{widget.href}" target="{widget.hrefTarget:r}"><IMG src="images/{widget.img}" width="11" height="12" border="0" alt="{widget.label:h}"></a></TD>
<TD class="CommonButtonBG" nowrap>&nbsp;&nbsp;<a href="{widget.href}" target="{widget.hrefTarget:r}" class="ButtonLink"><FONT IF="widget.label" class="Button">{widget.label:h}</FONT></a>&nbsp;&nbsp;</TD>
EOT;
	$replace_str = <<<EOT
<TD IF="widget.img" class="CommonButtonBG" nowrap><a href="{widget.href:t}" target="{widget.hrefTarget:r}"><IMG src="images/{widget.img}" width="11" height="12" border="0" alt="{widget.label:h}"></a></TD>
<TD class="CommonButtonBG" nowrap>&nbsp;&nbsp;<a href="{widget.href:t}" target="{widget.hrefTarget:r}" class="ButtonLink"><FONT IF="widget.label" class="Button">{widget.label:h}</FONT></a>&nbsp;&nbsp;</TD>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>