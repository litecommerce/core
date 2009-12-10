<?php

$source = strReplace('<a href="admin.php?target=cleanup">Cleanup cache</a><br>', '', $source, __FILE__, __LINE__);
$source = strReplace('<a href="admin.php?target=searchStat">Search statistics</a><br>', '<a href="admin.php?target=orders_stats">Statistics</a><br>', $source, __FILE__, __LINE__);
$source = strReplace('<widget module="XCartImport" template="modules/XCartImport/menu.tpl">', '<widget module="XCartImport" template="modules/XCartImport/menu.tpl">'."\n".'<hr color=black>'."\n".'<a href="admin.php?target=cleanup">Cleanup cache</a><br>', $source, __FILE__, __LINE__);

?>
