<p class="Text">
This section is used to build HTML version of LiteCommerce customer interface. HTML catalog can make your site search engine friendly. Modern search engines give lower priority to script built pages than to plain HTML pages, HTML catalog will help you to avoid this disadvantage. Customer will browse your HTML catalog, but when he decides to add product to cart, do search etc. he will be automatically redirected to script version of the cart.
<p class="Text">
HTML catalog will be located in '{catalog.catalogDir}' directory under LiteCommerce directory, you can access HTML catalog via URL:<br>
<br>
<a IF="{catalog.built}" href="{catalog.url}"><b><u>{catalog.url}</u></b></a>
<u IF="{!catalog.built}">{catalog.url}</u>
<p class="Text">
For UNIX users, make sure that '{catalog.catalogDir}' directory has world writable permissions (go to LiteCommerce directory and issue the following command: chmod 0777 {catalog.catalogDir}), after building catalog do not forget to set permissions back to 0755. 
<p class="Text">
<b>Note:</b> HTML catalog is a set of static pages, it does not reflect changes in your database, so when you add new products, change pricing etc., do not forget to refresh your catalog (build it again) or install AutoUpdateCatalog module.
<p class="Text" IF={!memoryLimitChangeable}>
<b>WARNING!</b> The memory limit is set to {memoryLimit} and it cannot be increased on the fly, that is why complicated HTML catalog pages may not be generated successfully. You can increase the value of the memory_limit option in php.ini file to make HTML catalog generation possible.</p>
<p class="Text">
<a href="admin.php?target=catalog&action=build"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"><b> Build catalog</b></a>
<p class="Text">
<a href="admin.php?target=catalog&action=clear"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"><b> Remove/Clear catalog </b></a>

<p IF="mode=#removed#" class="SuccessMessage" >&gt;&gt; HTML catalog was removed &lt;&lt;</p>

<p IF="mode=#success#" class="SuccessMessage">&gt;&gt; HTML catalog has been successfully built &lt;&lt;</p>
