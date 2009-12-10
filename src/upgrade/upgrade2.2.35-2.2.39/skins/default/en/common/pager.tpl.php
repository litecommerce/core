<?php

	$find_str = <<<EOT
Result pages:&nbsp;{foreach:pageUrls,num,pageUrl}{if:isCurrentPage(num)}<b>[{num}]</b>{else:}<a href="{pageUrl}">[<u>{num}</u>]</a>{end:}&nbsp;{end:}
EOT;
	$replace_str = <<<EOT
Result pages:&nbsp;{foreach:pageUrls,num,pageUrl}{if:isCurrentPage(num)}<b>[{num}]</b>{else:}<a href="{pageUrl}">[<u>{num}</u>]</a>{end:} {end:}
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>