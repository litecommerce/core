<?php

$source = strReplace('<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">', '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">'."\n".'<meta name="ROBOTS" content="NOINDEX">'."\n".'<meta name="ROBOTS" content="NOFOLLOW">', $source, __FILE__, __LINE__);

$source = strReplace('<widget target="searchStat" template="common/dialog.tpl" body="searchStat.tpl" head="Search statistics">', '<widget target="extra_fields" template="common/dialog.tpl" body="product/extra_fields_form.tpl" head="Product extra fields">'."\n".'<widget template="stats.tpl">', $source, __FILE__, __LINE__);

?>
