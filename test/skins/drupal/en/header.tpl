{* SVN $Id$ *}

<HEAD>
    <script type="text/javascript">
      if (document.getElementById("rebuild_cache_block")) document.getElementById("rebuild_cache_block").style.display = "none";
    </script>

<TITLE>LiteCommerce online store builder
{if:target=#category#&!title=##} - {end:}
{if:target=#product#&!title=##} - {end:}
{if:target=#cart#} - Your Shopping Cart{end:}
{if:target=#help#} - Help section{end:}
{if:target=#checkout#} - Checkout{end:}
{if:target=#checkoutSuccess#} - Thank you for your order{end:}
{if:target=#main#&!page=##} - {extraPage.title:h}{end:}
{title:h}
</TITLE>
<META http-equiv="Content-Type" content="text/html; charset={charset}">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<META IFF="!metaDescription" name="description" content="The powerful shopping cart software for web stores and e-commerce enabled stores is based on PHP / PHP4 with SQL database with highly configurable implementation based on templates.">
<META IFF="metaDescription" name="description" content="{metaDescription:r}">
<META IFF="keywords" name="keywords" content="{keywords:r}">

<link href="{getSkinURL(#style.css#)}"  rel="stylesheet" type="text/css">
<link FOREACH="getCSSResources(),file" href="{file}" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="{getSkinURL(#js/jquery-1.3.2.js#)}"></script>
<script type="text/javascript" src="{getSkinURL(#js/common.js#)}"></script>
<script FOREACH="getJSResources(),file" type="text/javascript" src="{file}"></script>

</HEAD>

