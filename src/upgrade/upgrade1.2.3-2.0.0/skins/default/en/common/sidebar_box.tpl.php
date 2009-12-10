<?php

$source = strReplace('<td class="SidebarTitle" background="images/sidebartitle_back.gif">&nbsp;{image.display()}&nbsp;&nbsp;{head.display()}</td>', '<td class="SidebarTitle" background="images/sidebartitle_back.gif">&nbsp;<widget template="{widget.dir}/image.tpl"/>&nbsp;&nbsp;{widget.head:h}</td>', $source, __FILE__, __LINE__);
$source = strReplace('{body.display()}', '<widget template="{widget.dir}/body.tpl"/>', $source, __FILE__, __LINE__);

?>
