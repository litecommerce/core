<?php include "start.php"; ?>
<h1>Migrating from other shopping cart</h1>
<h2>1. Import your product catalog from your previous shopping cart</h2>

<p>Handy product importing feature implemented in LiteCommerce helps you import your products and categories from other 
shopping cart software, databases and/or worksheets. To import the catalog data, perform the following steps:</p>


<h3>1.1. Prepare your data</h3>
<p>To take advantage of LiteCommerce import funtionality, you need to convert your data into "comma-separated values" (CSV) format. 
Some shopping carts provide this functinality natively. Alternatively, you can use one of the many tools that can perform the task 
(MS Excel<sup><font size="-2">TM</font></sup>, PHPMyAdmin or other software of your choice). </p>
<p>If you are using MS Excel<sup><font size="-2">TM</font></sup>, open the catalog database and select the 'File/Save As'
option and choose the CSV file type. </p>

<p><strong>Important:</strong> Open the generated CSV file using a text editor (Notepad, vi or other) and check it for 
header and footer lines. Remove all headers and footers from the file to avoid incorrect data import.</p>

<p><strong>Hint:</strong> One product can be imported into several categories at once. To do so, create a separate data field
for category names. Next make a duplicate copy the product-related data line and specify different category names for two instances 
of the product. Category names should have the 'category/subcategory/...' format. When you import the data, LiteCommerce will 
automatically create the corresponding categories and place the products there.</p>


<h3>1.2. Create the default product category</h3>
<p>Your catalog must contain at least one product category to place your products there, since LiteCommerce software 
does not allow to place products outside catalog categories.</p> 
<p>Create the default category in the 
<a href="../admin.php?target=category&category_id=0&mode=add" target="right"><strong>Catalog / Categories / Add new...</strong></a> section.</p>
<p><strong>Note:</strong> You can skip this step if you have a <em>'ñategory'</em> column in your catalog data CSV file.</p>


<h3>1.3. Import catalog data</h3>
<p>After you have created the CSV file with the catalog data, you can import the data into your LiteCommerce-based online store.  
Switch to the <a href="../admin.php?target=import_catalog" target="right"><strong>Import catalog</strong></a> section of the 
<strong>Catalog</strong> menu. 
Specify the order in which data fields are present in your CSV file and set the remaining fields to <em>'NULL'</em>.</p>
<p><strong>Important:</strong> The <em>'name'</em> field containing the name of the product is mandatory and cannot be omitted.</p>
<p><strong>Hint:</strong> To import product thumbnails and images along with product descriptions, include the <em>'thumbnail'</em> 
and <em>'image'</em> columns in the list of fields being imported. The corresponding fields in the CSV file must contain the names 
of image files (please note, that most web-servers are case-sensitive, and 'image.gif' and 'IMAGE.GIF' would refer to 
different files; it is recommended to use lower case for all file names). Prior to importing the CSV file, upload your 
images into a folder at your website via FTP and specify the location of the folder in the 'Directory where images are 
located' field of the <strong>Import products</strong> page.</p>
<p>After you compete the form, click on the 'Import products' button. </p>
<p><strong>Note:</strong> If you experience difficulties in preparing or importing your data, see the 
<a href="importExamples.php"><img src="go.gif" width="13" height="13" border="0" align="absmiddle"><strong>Catalog import examples</strong></a> 
section of the guide to learn more about preparing and importing catalog data.</p>

<h2>2. Check the catalog</h2>
<p>After you have imported the catalog data, it is highly recommended to check the appearance of imported products at your store catalog. 
Go to the <a href="../cart.php" target="right"><strong>Customer Zone</strong></a> of your store and examine the catalog. Categories 
appear at the top of the left box (if your store has the 3-column design) or in the cenral area (in case of the 2-column design) 
of the Customer Zone. If the catalog has been imported incorrectly, check and correct your input data,
<a href="../admin.php?target=categories&category_id=0" target="right">clear the catalog data</a> and import the file again.</p>


<h2>3. Edit your products and categories</h2>
<p>Imported catalog data is stored in the database of your online store and can be edited directly in the Administrator Zone.</p>

<h3>3.1. Edit categories</h3>
<p>To edit categories proceed to the 
<a href="../admin.php?target=categories&category_id=0" target="right"><strong>Catalog / Categories</strong></a> section, 
which contains a list of top-level categories. 
Click on the 'Add new ...' button at the bottom of the page to create a new category,
click on the 'Modify' link next to the category name to edit it, or 
click on the name of the category to review the list of its subcategories.</p>

<h3>3.2. Edit products</h3>
To edit products proceed to the <a href="../admin.php?target=product_list" target="right"><strong>Catalog / Products</strong></a> section. 
Use the 'Search product' form to locate the product you want to edit. 
<p><strong>Hint:</strong> Leave the form fields empty and click on the 'Search' button to see the complete list.</p>
<p>Click in the product name to switch to the product details page. </p>
<p><strong>Hint:</strong> The <em>'Brief Description'</em> field is displayed in the category listing and 
the <em>'Detailed Description'</em> field is displayed in the product description page. If you leave the 
<em>'Detailed Description'</em> field blank, the <em>'Brief Description'</em> field will be displayed in the 
product description page as well.</p>

<h2>4. Customise your store front-end</h2>
<p>Decide whether you want to use LiteCommerce automatic product catalog or your existing website pages as the customer front-end:</p>
<ul>
<li>I am going to use my existing catalog pages (suitable for smaller online stores).<br>
<a href="addon.php"><img src="go.gif" width="13" height="13" border="0" align="absmiddle"> Integrate
LiteCommerce shopping cart and checkout mechanists into your existing e-commerce website.</a></li>

<li>I am going to use dynamic LiteCommerce catalog (this is the preferred option).<br>
<a href="catalog.php"><img src="go.gif" width="13" height="13" border="0" align="absmiddle"> Create and 
customize your online store using comprehensive LiteCommerce functionality.</a></li>
</ul>

<?php include "end.php"; ?>
