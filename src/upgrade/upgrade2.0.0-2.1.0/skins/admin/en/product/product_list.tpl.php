<?php

$source = strReplace('<input type="button" value=" Delete "', '<input type="button" value=" Clone " onClick="products_form.action.value=\'clone\'; products_form.submit()">&nbsp;&nbsp;'."\n".'<input type="button" value=" Delete "', $source, __FILE__, __LINE__);

?>
