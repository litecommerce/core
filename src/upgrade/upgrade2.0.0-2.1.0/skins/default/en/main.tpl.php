<?php

$titleReplace =<<<EOT
{if:target=#category#} - {category.name:h}{end:}
{if:target=#product#} - {product.name:h}{end:}
{if:target=#cart#} - Your Shopping Cart{end:}
{if:target=#help#} - Help section{end:}
{if:target=#checkout#} - Checkout{end:}
{if:target=#checkoutSuccess#} - Thank you for your order{end:}
{if:target=#main#&!page=##} - {extraPage.head}{end:}
{title:h}
</title>
EOT;

$source = strReplace('</title>', $titleReplace, $source, __FILE__, __LINE__);
$source = strReplace('<meta name="description" content="The powerful shopping cart software for web stores and e-commerce enabled stores is based on PHP / PHP4 with SQL database (MySQL, Postgres) with highly configurable implementation based on templates.">', '<META if="!description" name="description" content="The powerful shopping cart software for web stores and e-commerce enabled stores is based on PHP / PHP4 with SQL database with highly configurable implementation based on templates."/>'."\n".'<META if="description" name="description" content="{description:r}"/>'."\n".'<META if="keywords" name="keywords" content="{keywords:r}"/>', $source, __FILE__, __LINE__);

$source = strReplace('<widget target="checkout" mode="register" class="CRegisterForm" template="common/dialog.tpl" body="register_form.tpl" head="New member" name="registerForm" allowAnonymous>', '<widget target="checkout" mode="register" class="CRegisterForm" template="common/dialog.tpl" body="register_form.tpl" head="New member" name="registerForm" allowAnonymous="{config.General.enable_anon_checkout}">'."\n".'<widget target="profile" mode="login" template="common/dialog.tpl" head="Authentication" body="authentication.tpl">', $source, __FILE__, __LINE__);

$source = strReplace('You have got bonus', 'You have earned bonus', $source, __FILE__, __LINE__);

$source = strReplace('{profiler.display()}', '', $source, __FILE__, __LINE__);

?>
