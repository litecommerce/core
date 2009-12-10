<?php

$source = strReplace('<span IF="products">'."\n".'{pager.display()}', '<widget class="CPager" data="{products}" name="pager">', $source, __FILE__, __LINE__);
$source = strReplace('<p FOREACH="products,product">', '<p FOREACH="pager.pageData,product">', $source, __FILE__, __LINE__);
$source = strReplace('{pager.display()}'."\n\n".'</span>', '<widget name="pager">', $source, __FILE__, __LINE__);

?>
