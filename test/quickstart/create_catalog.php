<?php include "start.php"; ?>
<h1>Create product catalog</h1>

<h3>1. Create product categories</h3>

<p>New product categories can be created in the 
<a href="../admin.php?target=categories&category_id=0" target="right"><strong>Catalog / Categories</strong></a> section. 
All top-level categories of your store are listed there 
(these categories are reflected at the front-end of your store's <a href="../cart.php" target="right"><strong>Customer Zone</strong></a>).</p>
<p>To create a new product category click on the 'Add new...' button. Fill in the fields of the category creation form and 
click on the 'Submit' button.</p>
<p>To edit a category select the circle to the left of its name and click 'Modify'. 
To go inside a category and create sub-categories click the category name. You can then use the 'Add new' button to create a subcategory.</p>

<h3>2. Create products</h3>
<p>To create a new product proceed to the 
<a href="../admin.php?target=add_product" target="right"><strong>Catalog / Add product</strong></a> section. 
To edit a product go to the <a href="../admin.php?target=product_list" target="right"><strong>Catalog / Products</strong></a> page. 
This will take you to the product search form. To list all available products leave the form empty and click on the 'Search' button. 
You can browse pages using the 'Result pages:' links.</p>
<p>By clicking the product name you enter the product modification page. The 'Brief Description' field is used in product listings, 
and the 'Detailed Description' is used in the product details page. If no 'Detailed Description' is specified, the 
'Brief Description' is used instead.</p>

<h2>3. Decide whether you want to use LiteCommerce automatic product catalog or your existing website pages as the customer front-end</h2>

<ul>
<li>I am going to use my existing catalog pages (suitable for smaller online stores).<br>
<a href="addon.php"><img src="go.gif" width="13" height="13" border="0" align="absmiddle"> Integrate
LiteCommerce shopping cart and checkout mechanists into your existing e-commerce website.</a></li>

<li>I am going to use dynamic LiteCommerce catalog (this is the preferred option).<br>
<a href="catalog.php"><img src="go.gif" width="13" height="13" border="0" align="absmiddle"> Create and 
customize your online store using comprehensive LiteCommerce functionality.</a></li>
</ul>

<?php include "end.php"; ?>

