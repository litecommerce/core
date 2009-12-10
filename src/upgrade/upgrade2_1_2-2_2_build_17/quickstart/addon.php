<?php include "start.php"; ?>
<h1>Integrating LiteCommerce shopping cart into your existing store</h1>

<p>To take advantage of LiteCommerce shopping cart functionality only minor modifications to your existing website are needed. 
The modifications consist in creating 'Add to cart' links on product pages to enable your customers to add products to the shopping cart
and review the cart. Adding direct links to the shopping cart is a good idea as well.</p>

<h2>1. Create an 'Add to cart' link</h2>
<p>Locate a product using the  
<a href="../admin.php?target=product_list" target="right"><strong>Search Product</strong></a> page. 
Click on the product name to proceed to the product info page and switch to the 'HTML links' tab. 
Follow the instructions provided on that page to create an 'Add to cart' link or button. </p>

<h2>2. Create a 'Shopping cart' link</h2>
<p>Copy the code from one of the following text areas into existing website page to add a 'Shopping cart' link or button.</p>
<h3>Plain shopping cart link</h3>
<textarea cols="40" rows="5"><a href="<?php echo preg_replace('![^/]+/[^/]*$!', '', $_SERVER['SCRIPT_NAME']); ?>cart.php?target=cart">Shopping cart</a></textarea>
<h3>Shopping cart button</h3>
<textarea cols="40" rows="5"><input type="button" value="Add to cart" onclick="document.location='<?php echo preg_replace('![^/]+/[^/]*$!', '', $_SERVER['SCRIPT_NAME']); ?>cart.php?target=cart'"></textarea>

<h2>3. Switch LiteCommerce software to Checkout Desk mode</h2>
<p>Proceed to the <a href="../admin.php?target=settings" target="right"><strong>Settings / General settings</strong></a> section, 
place a checkmark against the 'Switch the shop to "Checkout desk" operation mode' option and click on the "Submit" button. 
This disables unnecessary LiteCommerce components retaining only shopping cart and checkout functionality.</p>

<h2>4. Customize the look&amp;feel of the checkout page</h2>
<p>When LiteCommerce software operates in the Checkout Desk mode, the '<a href="../cart.html">cart.html</a>' file
is used as a design template instead of the main template of the Customer Zone ('skins/default/en/mail.tpl'). This
file can be edited in any HTML editor to customize the look and feel of the checkout page. It is advisable to create a backup copy
of the file before editing it.</p>

<p><b>Important:</b> When editing the 'cart.html' file, do not delete text in curly brackets {} or special tags named &lt;widget&gt;.</p>

<p>
<a href="configure.php"><img src="go.gif" width="13" height="13" border="0" align="absmiddle"> Further configuration settings</a>
</p>
<?php include "end.php"; ?>

