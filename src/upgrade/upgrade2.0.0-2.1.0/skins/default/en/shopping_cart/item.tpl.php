<?php

$source = strReplace('<a href={item.url:h}" IF="item.hasThumbnail()"><img src="{item.thumbnailURL:h}" border="0" width="70"></a>', '<a href="{item.url:h}" IF="item.hasThumbnail()"><img src="{item.thumbnailURL:h}" border="0" width="70"></a>', $source, __FILE__, __LINE__);
$source = strReplace('<widget module="ProductOptions" template="modules/ProductOptions/selected_options.tpl" visible="{item.hasOptions()}" item="{item}">', '<span IF="{item.weight}">Weight: {item.weight} {config.General.weight_symbol}<br></span>', $source, __FILE__, __LINE__);

?>
