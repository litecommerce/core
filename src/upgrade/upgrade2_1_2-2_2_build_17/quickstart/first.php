<?php include "start.php"; ?>
<h1>LiteCommerce Quick Start Wizard</h1>

<p>The Quick Start Wizard takes you through the process of setting up your store.</p>
<p><strong>Note:</strong> You can access the Wizard from the <strong>Settings</strong> menu of the Administrator 
Zone of the store when you need it.</p>

<h2>1. Log into the Administrator Zone.</h2>
<p>Go to the <a href="../admin.php" target="right"><strong>Administrator Zone</strong></a> of your store, type in the
administrator's login and password you specified during the installation and click on the 'Submit' button.</p>

<h2>2. Clear demo data.</h2>
<p>If during the installation of LiteCommerce software you opted to have the demo product catalog installed, 
you need to remove it before adding your own products. To clear the catalog, go to the 
<a href="../admin.php?target=categories&category_id=0" target="right"><strong>Catalog / Categories</strong></a> section 
of the Administrator Zone. The section contains a list of top-level categories. Click on the 'Delete All' button at 
the bottom of the category list to remove all the catalog data.</p>

<h2>3. Create product catalog/Migrate product catalog from the previous shopping cart.</h2>
<p>At this step you need to decide whether you want to create a new product catalog, or migrate your store data from 
the previous shopping cart:</p>
<ul>
<li><a href="migrate.php"><img src="go.gif" width="13" height="13" border="0" align="absmiddle"> I am switching 
from another shopping cart and want to transfer my product catalog to LiteCommerce</a></li>
<li><a href="create_catalog.php"><img src="go.gif" width="13" height="13" border="0" align="absmiddle"> I am going to 
create my store catalog from scratch</a></li>
</ul>

<?php include "end.php"; ?>

