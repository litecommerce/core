<?php

$source = strReplace('<' . '?php echo '. "'<" . '?xml version="1.0"?' . ">'; ?" . '>', '{startXml:h}', $source, __FILE__, __LINE__);

?>
