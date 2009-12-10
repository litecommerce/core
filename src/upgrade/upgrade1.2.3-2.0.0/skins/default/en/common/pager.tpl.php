<?php

$source = strReplace('moreThanOnePage()', 'moreThanOnePage', $source, __FILE__, __LINE__);
$source = strReplace('Result pages:&nbsp;{getPageLinks():h}', 'Result pages:&nbsp;{foreach:pageUrls,num,pageUrl}{if:isCurrentPage(num)}<b>[{num}]</b>{else:}<a href="{pageUrl:h}">[<u>{num}</u>]</a>{end:}&nbsp;{end:}', $source, __FILE__, __LINE__);

?>
