<?php

$source = strReplace('<a href="admin.php?target=categories&category_id={cat.category_id}">{cat.name}</a>', '<a href="admin.php?target=categories&category_id={cat.category_id}">{cat.name:h}</a>{if:!cat.enabled}&nbsp;&nbsp;<font color=red>(disabled)</font>{end:}', $source, __FILE__, __LINE__);

?>
