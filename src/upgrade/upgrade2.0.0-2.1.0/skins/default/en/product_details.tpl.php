<?php

$source = strReplace('<tr IF="{product.weight}">', '<widget class="CExtraFields" template="extra_fields.tpl">'."\n".'<tr IF="{product.weight}">', $source, __FILE__, __LINE__);

?>
