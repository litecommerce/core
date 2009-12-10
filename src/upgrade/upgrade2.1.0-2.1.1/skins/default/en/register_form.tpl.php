<?php
   $source = strReplace('{*extraFields*}', 
'{*extraFields*}
<widget module="Newsletters" template="modules/Newsletters/subscription_form.tpl">', $source, __FILE__, __LINE__);
?> 
