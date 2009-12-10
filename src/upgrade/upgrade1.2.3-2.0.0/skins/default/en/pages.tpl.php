<?php

// OLD: {test_page.display(#Test page#)}
// NEW: <widget template="common/dialog.tpl" body="test_page.tpl" head="Test page" visible="{page=#test_page#}">

$source = preg_replace("/{(\w+)\.display\(#([^#]*)#\)}/", "<widget template=\"common/dialog.tpl\" body=\"\\1.tpl\" head=\"\\2\" visible=\"{page=#\\1#}\">", $source);

?>
