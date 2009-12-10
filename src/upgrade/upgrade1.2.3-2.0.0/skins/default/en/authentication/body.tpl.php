<?php

$source = strReplace("<td>&nbsp;</td>", "<td>&nbsp;&nbsp;&nbsp;</td>", $source, __FILE__, __LINE__);
$source = strReplace("{wrap(user,#login#,#20#)}<br>", "{wrap(auth.profile,#login#,#20#)}<br>", $source, __FILE__, __LINE__);

?>
