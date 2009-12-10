<?php
    $source = strReplace('"{product.weight}"','"{!product.weight=0}"', $source, __FILE__, __LINE__);
?>
