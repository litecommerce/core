<?php

$source = strReplace('<input type="hidden" name="action" value="search">', '<input type="hidden" name="mode" value="search">', $source, __FILE__, __LINE__);
$source = strReplace('{order_status.display()}', '<widget class="CStatusSelect" field="status" allOption>', $source, __FILE__, __LINE__);
$source = strReplace('{start_date.display()}', '<widget class="CDate" field="startDate">', $source, __FILE__, __LINE__);
$source = strReplace('{end_date.display()}', '<widget class="CDate" field="endDate">', $source, __FILE__, __LINE__);

?>
