<?php

$source = strReplace('<a href="cart.php?target=product&product_id={product_id}" IF="hasThumbnail()"><img src="{getThumbnailURL()}" border="0" width="70"></a>', '<a href={item.url:h}" IF="item.hasThumbnail()"><img src="{item.thumbnailURL:h}" border="0" width="70"></a>', $source, __FILE__, __LINE__);
$source = strReplace('<a href="cart.php?target=product&product_id={product_id}"><FONT class="ProductTitle">{name}</FONT></a><br><br>', '<a href="{item.url:h}"><FONT class="ProductTitle">{item.name}</FONT></a><br><br>', $source, __FILE__, __LINE__);
$source = strReplace('{truncate(dialog,#brief_description#,#300#):h}<br>', '{truncate(item.brief_description,#300#):h}<br>', $source, __FILE__, __LINE__);
$source = strReplace('{product_options.display(dialog)}', '<widget module="ProductOptions" template="modules/ProductOptions/selected_options.tpl" visible="{item.hasOptions()}" item="{item}">', $source, __FILE__, __LINE__);
$source = strReplace('<FONT class="ProductDetailsTitle">SKU:</FONT> <FONT class="ProductDetails">{sku}</FONT><br>', '<FONT IF="{item.sku}" class="ProductDetails">SKU: {item.sku}<br></FONT>', $source, __FILE__, __LINE__);
$source = strReplace('<FONT class="ProductPriceTitle">Price:</FONT> <FONT class="ProductPriceConverting">{price_format(dialog,#price#):h}&nbsp;x&nbsp;</FONT>', '<FONT class="ProductPriceTitle">Price:</FONT> <FONT class="ProductPriceConverting">{price_format(item,#price#):h}&nbsp;x&nbsp;</FONT>', $source, __FILE__, __LINE__);
$source = strReplace('<input type="text" name="amount[{id}]" value="{amount}" size="2" maxlength="3">', '<input type="text" name="amount[{cart_id}]" value="{item.amount}" size="2" maxlength="3">', $source, __FILE__, __LINE__);
$source = strReplace('<FONT class="ProductPrice">{price_format(dialog,#total#):h}</FONT>', '<FONT class="ProductPrice">{price_format(item,#total#):h}</FONT>', $source, __FILE__, __LINE__);
$source = strReplace('<a href="cart.php?target=cart&action=delete&cart_id={id}"><FONT class="FormButton"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Delete item</FONT></a>', '<a href="cart.php?target=cart&action=delete&cart_id={cart_id}"><FONT class="FormButton"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Delete item</FONT></a>', $source, __FILE__, __LINE__);

?>
