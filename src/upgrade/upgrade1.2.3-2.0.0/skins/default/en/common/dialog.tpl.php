<?php

$source = strReplace('<td height="20"  class=DialogTitle background="images/dialog_bg1.gif" valign="bottom">&nbsp;&nbsp;{head}</td>', '<td height="20"  class=DialogTitle background="images/dialog_bg1.gif" valign="bottom">&nbsp;&nbsp;{widget.head}</td>', $source, __FILE__, __LINE__);
$source = strReplace('<td class=DialogBox>{body.display()}&nbsp;</td>', '<td class=DialogBox><widget template="{widget.body}">&nbsp;</td>', $source, __FILE__, __LINE__);

?>
