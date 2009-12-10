<?php
    $source = strReplace('<a href="{widget.href:r}">','<a href="{widget.href:r}" target="{widget.hrefTarget:r}">', $source, __FILE__, __LINE__);
?>
