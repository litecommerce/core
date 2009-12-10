<?php

$source = strReplace("<span IF=\"category.products\">\n\n{pager.display()}", '<widget class="CPager" data="{category.products}" name="pager">', $source, __FILE__, __LINE__);
$source = strReplace('<p FOREACH="products,product">', '<p FOREACH="pager.pageData,product">', $source, __FILE__, __LINE__);
$source = strReplace("{pager.display()}\n\n</span>", '<widget name="pager">', $source, __FILE__, __LINE__);
$source = strReplace("<span IF=\"!category.products\">\nThere are no avalable products in this category.\n</span>", '', $source, __FILE__, __LINE__);

?>
