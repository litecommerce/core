<?php

$source = strReplace('</font>', '<span IF="target=#profile#&mode=#login#">&nbsp;::&nbsp;Authentication</span>'."\n".'</font>', $source, __FILE__, __LINE__);

?>
