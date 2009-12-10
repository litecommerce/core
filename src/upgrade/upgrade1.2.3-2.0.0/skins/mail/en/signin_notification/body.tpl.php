<?php

$source = "{*   E-mail sent to customer after successful signup *}\n" . $source;
$source = preg_replace("/{(profile\.\w+)(\:h)}/", "{\\1}", $source);

?>
