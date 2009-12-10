<?php
     $source = strReplace('{*extraFields*}',
'<widget module="Newsletters" template="modules/Newsletters/subscription_form.tpl">
{*extraFields*}', $source, __FILE__, __LINE__); 
?> 
